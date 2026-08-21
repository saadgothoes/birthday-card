<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MusicTrack extends Model
{
    protected $fillable = [
        'title',
        'artist',
        'category',
        'file_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /**
     * Where to play this track from.
     *
     * The stream route rather than the file's public URL, because seeking
     * needs Range requests answered and a static file is only served as well
     * as whatever is in front of the app happens to do it.
     *
     * @see \App\Http\Controllers\MusicStreamController
     */
    public function getUrlAttribute(): string
    {
        // Relative, so the URL is always same-scheme as the page asking for
        // it — an absolute one built from the request comes back http:// behind
        // a proxy that doesn't forward the scheme, and an https page will
        // refuse to load it.
        return route('music.stream', $this->id, false);
    }
}
