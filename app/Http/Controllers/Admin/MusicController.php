<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MusicTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MusicController extends Controller
{
    public function index()
    {
        return view('admin.music.index', [
            'tracks' => MusicTrack::latest()->get(),
        ]);
    }

    private function trackData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:120',
            'artist' => 'nullable|string|max:120',
            'category' => 'required|in:english,hindi',
        ]);
    }

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|uuid',
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1|max:100',
            'extension' => 'required|in:mp3,mp4,m4a,wav,ogg',
        ]);

        $key = 'admin_music_upload_' . $request->user()->id . '_' . $request->upload_id;
        $upload = $request->session()->get($key);
        if (! $upload) {
            $upload = [
                'path' => 'music-library/.upload-' . $request->upload_id . '.tmp',
                'next' => 0,
                'extension' => $request->extension,
            ];
        }
        abort_if((int) $request->chunk_index !== (int) $upload['next'], 422, 'Invalid upload order.');

        $content = $request->getContent();
        abort_if(strlen($content) > 2 * 1024 * 1024, 422, 'Upload chunk is too large.');
        Storage::disk('public')->makeDirectory('music-library');
        $absolute = Storage::disk('public')->path($upload['path']);
        file_put_contents($absolute, $content, FILE_APPEND | LOCK_EX);
        abort_if(filesize($absolute) > 100 * 1024 * 1024, 422, 'Music must be 100 MB or smaller.');
        $upload['next']++;
        $request->session()->put($key, $upload);

        return response()->json(['success' => true, 'next' => $upload['next']]);
    }

    public function finalizeChunked(Request $request)
    {
        $data = $this->trackData($request);
        $request->validate(['upload_id' => 'required|uuid', 'extension' => 'required|in:mp3,mp4,m4a,wav,ogg']);
        $key = 'admin_music_upload_' . $request->user()->id . '_' . $request->upload_id;
        $upload = $request->session()->pull($key);
        abort_if(! $upload || $upload['next'] < 1, 422, 'The music upload is incomplete.');

        $absolute = Storage::disk('public')->path($upload['path']);
        abort_if(! is_file($absolute), 422, 'The uploaded music file is missing. Please try again.');
        abort_if(filesize($absolute) > 100 * 1024 * 1024, 422, 'Music must be 100 MB or smaller.');
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($absolute);
        abort_if(! in_array($mime, ['audio/mpeg', 'audio/mp4', 'audio/x-m4a', 'audio/wav', 'audio/ogg', 'video/mp4'], true), 422, 'That file is not a supported music format.');

        $path = 'music-library/' . Str::uuid() . '.' . $request->extension;
        Storage::disk('public')->move($upload['path'], $path);
        $this->createTrack($data, $path);

        return response()->json(['success' => true]);
    }

    private function createTrack(array $data, string $path): MusicTrack
    {
        return MusicTrack::create([
            'title' => $data['title'],
            'artist' => $data['artist'] ?? null,
            'category' => $data['category'],
            'file_path' => $path,
            'is_active' => true,
        ]);
    }

    public function toggle(MusicTrack $track)
    {
        $track->update(['is_active' => ! $track->is_active]);

        return back()->with('success', 'Music visibility updated.');
    }

    public function destroy(MusicTrack $track)
    {
        Storage::disk('public')->delete($track->file_path);
        $track->delete();

        return back()->with('success', 'Music removed from the library.');
    }
}
