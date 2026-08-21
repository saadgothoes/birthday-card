<?php

namespace App\Http\Controllers;

use App\Models\MusicTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serves the music library's audio, one track per request.
 *
 * The story plays a chosen minute out of a track, which means the player has
 * to seek — and seeking needs the server to answer a Range request with the
 * bytes around that offset. Handing the files to the web server as plain
 * static assets left that to whatever happens to be in front of the app: the
 * built-in server answers a Range request with `200` and the whole 12 MB file,
 * so the browser cannot seek at all and playback simply stalls with no sound
 * and no error. A misconfigured nginx or a CDN in the way does the same thing.
 *
 * Routing the audio through here settles it: Symfony's BinaryFileResponse
 * implements Range itself, so every deployment answers `206` with a
 * `Content-Range` no matter what is serving the app.
 */
class MusicStreamController extends Controller
{
    /**
     * Content types by extension.
     *
     * Guessing from the file's own bytes is what the uploader already did;
     * what matters here is that the browser is told something it will decode
     * rather than a generic octet-stream, which some browsers refuse to play.
     */
    private const TYPES = [
        'mp3' => 'audio/mpeg',
        'm4a' => 'audio/mp4',
        'mp4' => 'audio/mp4',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
    ];

    /**
     * Stream one track.
     *
     * Inactive tracks are still served: hiding a song in the library stops it
     * being offered to new cards, but the cards already using it must keep
     * playing.
     */
    public function show(Request $request, MusicTrack $track): BinaryFileResponse
    {
        $path = Storage::disk('public')->path($track->file_path);

        abort_unless(is_file($path), 404);

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $response = response()->file($path, [
            'Content-Type' => self::TYPES[$extension] ?? 'application/octet-stream',
            'Accept-Ranges' => 'bytes',
        ]);

        // The file behind a track never changes — a new upload is a new row
        // with a new UUID — so it can be cached hard. That keeps a story's
        // music out of the network on every page but the first.
        $response->setMaxAge(60 * 60 * 24 * 30);
        $response->setPublic();

        return $response;
    }
}
