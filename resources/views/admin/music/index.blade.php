<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Library</title>
    <style>
        :root {
            --ink: #171a2b;
            --muted: #737993;
            --line: #e5e8f2;
            --soft: #f7f8fc;
            --accent: #5b5ef4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 38px;
            background: #f4f6fb;
            color: var(--ink);
            font-family: Arial, sans-serif;
        }

        main {
            max-width: 1100px;
            margin: auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 26px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 30px;
        }

        p {
            color: var(--muted);
            margin: 0;
        }

        a,
        button {
            font: inherit;
        }

        .back {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow: 0 8px 28px rgba(70, 80, 130, .06);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 160px 1.5fr auto;
            gap: 12px;
            align-items: end;
        }

        label {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        input,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 9px;
            padding: 11px;
            background: var(--soft);
        }

        button {
            border: 0;
            border-radius: 9px;
            padding: 11px 15px;
            cursor: pointer;
            font-weight: 700;
        }

        .primary {
            color: #fff;
            background: var(--accent);
        }

        .danger {
            color: #b42318;
            background: #fff1f0;
        }

        .muted {
            color: #fff;
            background: #77809a;
        }

        .notice {
            padding: 12px 14px;
            border-radius: 9px;
            background: #ecfdf5;
            color: #047857;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
        }

        audio {
            width: 230px;
            height: 34px;
        }

        .tag {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        @media (max-width:800px) {
            body {
                padding: 20px 12px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .panel {
                overflow: auto;
            }

            table {
                min-width: 720px;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="top">
            <div>
                <h1>Music Library</h1>
                <p>Upload default English and Hindi songs for client cards.</p>
            </div>
            <a class="back" href="{{ route('admin.dashboard') }}">← Dashboard</a>
        </div>
        @if(session('success')) <div class="notice">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="notice" style="background:#fff1f2;color:#be123c;">{{ $errors->first() }}</div> @endif
        <section class="panel">
            <form id="musicUploadForm" method="POST" action="#" enctype="multipart/form-data" onsubmit="return false">
                @csrf
                <div class="form-grid">
                    <div><label>Song title</label><input name="title" required maxlength="120" value="{{ old('title') }}"></div>
                    <div><label>Artist</label><input name="artist" maxlength="120" value="{{ old('artist') }}"></div>
                    <div><label>Category</label><select name="category">
                            <option value="english">English</option>
                            <option value="hindi">Hindi</option>
                        </select></div>
                    <div><label>MP3 or MP4 file</label><input id="musicFile" type="file" name="music" accept="audio/mpeg,audio/mp4,video/mp4,audio/*" required><small id="musicUploadStatus" style="display:block;color:var(--muted);margin-top:6px">Maximum 100 MB. Uploaded in small pieces.</small></div>
                    <button id="musicUploadButton" class="primary" type="button">+ Add Music</button>
                </div>
            </form>
        </section>
        <section class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Song</th>
                        <th>Category</th>
                        <th>Preview</th>
                        <th>Status</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tracks as $track)
                    <tr>
                        <td><strong>{{ $track->title }}</strong><br><small style="color:var(--muted)">{{ $track->artist ?: 'Unknown artist' }}</small></td>
                        <td><span class="tag">{{ $track->category }}</span></td>
                        <td><audio controls preload="none" src="{{ Storage::url($track->file_path) }}"></audio></td>
                        <td>{{ $track->is_active ? 'Visible to clients' : 'Hidden' }}</td>
                        <td>
                            <div class="actions">
                                <form method="POST" action="{{ route('admin.music.toggle', $track) }}">@csrf @method('PATCH')<button class="muted" type="submit">{{ $track->is_active ? 'Hide' : 'Show' }}</button></form>
                                <form method="POST" action="{{ route('admin.music.destroy', $track) }}" onsubmit="return confirm('Remove this song?')">@csrf @method('DELETE')<button class="danger" type="submit">Delete</button></form>
                            </div>
                        </td>
                    </tr>
                    @empty <tr>
                        <td colspan="5">No songs uploaded yet.</td>
                    </tr> @endforelse
                </tbody>
            </table>
        </section>
    </main>
    <script>
        const musicForm = document.getElementById('musicUploadForm');
        const musicFile = document.getElementById('musicFile');
        const musicStatus = document.getElementById('musicUploadStatus');
        const musicButton = document.getElementById('musicUploadButton');
        const csrf = musicForm.querySelector('input[name="_token"]').value;
        const chunkUrl = @json(route('admin.music.chunk'));
        const finalizeUrl = @json(route('admin.music.finalize'));

        musicButton.addEventListener('click', async () => {
            const file = musicFile.files[0];
            if (!file) return;
            if (file.size > 100 * 1024 * 1024) {
                musicStatus.textContent = 'Please choose a file up to 100 MB.';
                return;
            }

            const extension = file.name.split('.').pop().toLowerCase();
            if (!['mp3', 'mp4', 'm4a', 'wav', 'ogg'].includes(extension)) {
                musicStatus.textContent = 'Supported formats: MP3, MP4, M4A, WAV and OGG.';
                return;
            }

            const uploadId = crypto.randomUUID();
            const chunkSize = 1024 * 1024;
            const totalChunks = Math.ceil(file.size / chunkSize);
            const totalMb = (file.size / 1048576).toFixed(1);
            musicButton.disabled = true;
            musicStatus.textContent = 'Uploading 0.0 MB of ' + totalMb + ' MB (0%)';

            try {
                for (let index = 0; index < totalChunks; index++) {
                    const params = new URLSearchParams({
                        upload_id: uploadId,
                        chunk_index: index,
                        total_chunks: totalChunks,
                        extension
                    });
                    const response = await fetch(chunkUrl + '?' + params, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/octet-stream',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: file.slice(index * chunkSize, Math.min((index + 1) * chunkSize, file.size)),
                    });
                    if (!response.ok) {
                        const body = await response.json().catch(() => ({}));
                        throw new Error(body.message || 'Chunk upload failed at part ' + (index + 1) + '.');
                    }
                    const uploadedMb = (Math.min((index + 1) * chunkSize, file.size) / 1048576).toFixed(1);
                    const percent = Math.round(((index + 1) / totalChunks) * 100);
                    musicStatus.textContent = 'Uploading ' + uploadedMb + ' MB of ' + totalMb + ' MB (' + percent + '%)';
                }

                const formData = new FormData(musicForm);
                formData.delete('music');
                formData.append('upload_id', uploadId);
                formData.append('extension', extension);
                const finalResponse = await fetch(finalizeUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: formData,
                });
                if (!finalResponse.ok) {
                    const body = await finalResponse.json().catch(() => ({}));
                    throw new Error(body.message || 'Could not finish the music upload.');
                }
                musicStatus.textContent = 'Upload complete. Refreshing library…';
                window.location.reload();
            } catch (error) {
                musicStatus.textContent = error.message || 'Music upload failed. Please try again.';
                musicButton.disabled = false;
            }
        });
    </script>
</body>

</html>