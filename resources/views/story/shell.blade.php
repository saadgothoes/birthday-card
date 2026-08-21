{{--
    The story shell — the one document that stays put.

    Every card design is a standalone HTML document with its own <head>, its
    own styles and its own scripts; there are seventy-odd of them and no shared
    layout, which is why the story's navigation is injected after rendering
    rather than written into them (see App\Support\StoryChrome).

    That leaves nowhere to hang an <audio> element that survives a click on
    Next. A full navigation destroys the page and everything in it, so a player
    living inside a card can only ever be rebuilt on the next page and nudged
    back to roughly where it was — close, but audibly not the same song still
    playing.

    So this is the common layout the project never had. The shell holds the
    music; the story itself runs in a frame inside it. Moving through the story
    navigates the frame, and the shell — with the song in it — is never
    reloaded at all. The track is not resumed between pages because it never
    stopped.

    The card designs need no changes for this: they are already standalone
    documents that the dashboard previews in a frame, so they render here
    exactly as they do on their own.
--}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <title>{{ $title }}</title>

    {{--
        A shell that finds itself inside a frame means a navigation reached the
        server without the header that marks frame requests — older Safari, in
        practice. Swap in the page itself rather than nesting a second shell.
        This runs before the frame below is parsed, so nothing is loaded twice.
    --}}
    <script>
        if (window.top !== window.self) window.location.replace(@json($frameSrc));
    </script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #0d0b12;
            overflow: hidden;
        }

        #storyFrame {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        /* ── The now-playing badge ───────────────────────────────────────
           The music is the card's, not the recipient's: it starts itself once
           the story is unlocked and simply plays. So this is a sign, not a
           control — there is nothing here to press, no pause, no volume. It
           carries `pointer-events: none` throughout, which is what keeps it
           from ever taking a tap meant for the story underneath.

           It arrives with the song's name, then tucks itself away to a small
           badge of moving bars a few seconds later, so the page it sits over
           gets the corner back. */
        .np {
            --np-a: #ff9ec0;
            --np-b: #ffc978;
            position: fixed;
            left: max(14px, env(safe-area-inset-left));
            bottom: max(14px, env(safe-area-inset-bottom));
            z-index: 2147483000;
            pointer-events: none;
            font-family: 'DM Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            opacity: 0;
            transform: translateX(-10px);
            transition: opacity .45s ease, transform .45s cubic-bezier(.22, 1, .36, 1);
        }

        .np.ready {
            opacity: 1;
            transform: translateX(0);
        }

        .np[hidden] {
            display: none;
        }

        .np.side-boy {
            --np-a: #8dc5ff;
            --np-b: #a8e6ff;
        }

        .np.side-girl {
            --np-a: #ff9ec0;
            --np-b: #ffc978;
        }

        .np-pill {
            display: flex;
            align-items: center;
            gap: 9px;
            max-width: min(230px, calc(100vw - 28px));
            padding: 7px 14px 7px 7px;
            border-radius: 999px;
            background: rgba(16, 15, 22, .74);
            border: 1px solid rgba(255, 255, 255, .14);
            box-shadow: 0 8px 26px rgba(0, 0, 0, .38);
            backdrop-filter: blur(14px) saturate(1.4);
            -webkit-backdrop-filter: blur(14px) saturate(1.4);
            transition: max-width .5s cubic-bezier(.22, 1, .36, 1),
                        padding .5s cubic-bezier(.22, 1, .36, 1);
        }

        /* Tucked away: the text is gone and the pill closes around the bars. */
        .np.tucked .np-pill {
            max-width: 44px;
            padding: 7px;
        }

        .np-disc {
            position: relative;
            width: 30px;
            height: 30px;
            flex: none;
            border-radius: 50%;
            background: linear-gradient(145deg, var(--np-a), var(--np-b));
            display: grid;
            place-items: center;
            box-shadow: 0 0 0 0 rgba(255, 255, 255, .3);
            animation: np-pulse 2.6s ease-out infinite;
        }

        @keyframes np-pulse {
            0% {
                box-shadow: 0 0 0 0 color-mix(in srgb, var(--np-a) 55%, transparent);
            }

            70%,
            100% {
                box-shadow: 0 0 0 9px rgba(255, 255, 255, 0);
            }
        }

        .np-bars {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 12px;
        }

        .np-bars i {
            width: 2.5px;
            height: 100%;
            border-radius: 2px;
            background: rgba(18, 16, 24, .85);
            transform-origin: bottom;
            transform: scaleY(.3);
            animation: np-bounce .95s ease-in-out infinite;
            animation-play-state: paused;
        }

        .np.playing .np-bars i {
            animation-play-state: running;
        }

        .np-bars i:nth-child(2) {
            animation-delay: .16s;
        }

        .np-bars i:nth-child(3) {
            animation-delay: .32s;
        }

        .np-bars i:nth-child(4) {
            animation-delay: .48s;
        }

        @keyframes np-bounce {

            0%,
            100% {
                transform: scaleY(.28);
            }

            50% {
                transform: scaleY(1);
            }
        }

        .np-text {
            min-width: 0;
            overflow: hidden;
            opacity: 1;
            transition: opacity .3s ease;
        }

        .np.tucked .np-text {
            opacity: 0;
        }

        .np-title {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .01em;
            color: #f4f1f6;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .np-artist {
            display: block;
            margin-top: 1px;
            font-size: 10px;
            color: rgba(244, 241, 246, .56);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 560px) {
            .np {
                left: max(10px, env(safe-area-inset-left));
                bottom: max(10px, env(safe-area-inset-bottom));
            }

            .np-pill {
                max-width: min(190px, calc(100vw - 20px));
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .np,
            .np-pill,
            .np-text {
                transition: none;
            }

            .np-bars i,
            .np-disc {
                animation: none;
            }
        }

        @media print {
            .np {
                display: none;
            }
        }
    </style>
</head>

<body>
    <iframe id="storyFrame" src="{{ $frameSrc }}" title="{{ $title }}" allow="autoplay; fullscreen; encrypted-media"
        allowfullscreen></iframe>

    @if ($music)
        <div class="np side-{{ $side }}" id="npBadge" hidden aria-hidden="true">
            <div class="np-pill">
                <span class="np-disc">
                    <span class="np-bars"><i></i><i></i><i></i><i></i></span>
                </span>
                <span class="np-text">
                    <span class="np-title">{{ $music['title'] }}</span>
                    <span class="np-artist">{{ $music['artist'] ?: 'Now playing' }}</span>
                </span>
            </div>
        </div>

        <audio id="storyAudio" src="{{ $music['url'] }}" loop preload="auto" playsinline></audio>
    @endif

    <script>
        (function () {
            const FRAME_SRC = @json($frameSrc);
            const LOCK_PATH = @json($lockPath);
            // { url, start, end, title, artist } — the chosen track and the
            // stretch of it the client kept. A null end means the whole song.
            const MUSIC = @json($music);
            const KEY = @json($storageKey);

            const frame = document.getElementById('storyFrame');

            // ── The address bar ──────────────────────────────────────────
            // The shell never navigates, so without this a refresh or a shared
            // link would always land back on whichever page it opened at.

            function framePath() {
                try {
                    const at = frame.contentWindow.location;
                    return at.pathname + at.search;
                } catch (e) {
                    return null;
                }
            }

            function withoutFrameFlag(path) {
                return path.replace(/([?&])frame=1(&|$)/, '$1').replace(/[?&]$/, '');
            }

            // ── The music ────────────────────────────────────────────────
            // Created with the document and never torn down, so there is no
            // position to restore between pages and no gap to cover. The only
            // state kept is for a reload of the shell itself.

            const audio = document.getElementById('storyAudio');
            const badge = document.getElementById('npBadge');

            let started = false;
            let fading = false;

            function readState() {
                try {
                    const saved = JSON.parse(localStorage.getItem(KEY) || 'null');
                    // The window is checked as well as the track, so re-picking
                    // a card's music doesn't drop the new clip in at the old
                    // one's position.
                    if (saved && saved.src === MUSIC.url && saved.start === MUSIC.start && saved.end === MUSIC.end) {
                        return saved;
                    }
                } catch (e) {}
                return null;
            }

            function writeState() {
                if (!started) return;
                try {
                    localStorage.setItem(KEY, JSON.stringify({
                        src: MUSIC.url,
                        start: MUSIC.start,
                        end: MUSIC.end,
                        t: audio.currentTime,
                    }));
                } catch (e) {}
            }

            function clearState() {
                try { localStorage.removeItem(KEY); } catch (e) {}
            }

            function fadeIn() {
                if (fading) return;
                fading = true;
                const step = function () {
                    if (audio.volume >= 0.98) {
                        audio.volume = 1;
                        fading = false;
                        return;
                    }
                    audio.volume = Math.min(1, audio.volume + 0.045);
                    requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            }

            function showPlaying() {
                badge.classList.toggle('playing', !audio.paused);
            }

            // A reload spends the page's autoplay permission, and the shell's
            // own document may never have been clicked. The next touch — in
            // here or inside the frame — starts the music. Both documents are
            // armed, because a click inside a frame never reaches its parent.
            // This is not a control: nothing has to be aimed at, any touch
            // anywhere will do.
            function armGesture() {
                const scopes = [document];
                try {
                    if (frame.contentDocument) scopes.push(frame.contentDocument);
                } catch (e) {}

                scopes.forEach(function (scope) {
                    ['pointerdown', 'touchstart', 'keydown'].forEach(function (type) {
                        scope.addEventListener(type, resume, { capture: true, passive: true });
                    });
                });
            }

            function resume() {
                if (!started || !audio.paused) return;
                audio.play().then(function () {
                    showPlaying();
                    fadeIn();
                }).catch(function () {});
            }

            function play() {
                const going = audio.play();
                if (going && going.then) {
                    going.then(function () {
                        showPlaying();
                        fadeIn();
                    }).catch(function () {
                        showPlaying();
                        armGesture();
                    });
                } else {
                    showPlaying();
                    fadeIn();
                }
            }

            function seek(at) {
                if (!(at > 0)) return;
                const go = function () {
                    const duration = audio.duration;
                    if (isFinite(duration) && at > duration - 0.25) return;
                    try { audio.currentTime = at; } catch (e) {}
                };
                // readyState is checked as well as the event: a cached file can
                // have its metadata ready already, and then it never fires.
                if (audio.readyState >= 1) go();
                else audio.addEventListener('loadedmetadata', go, { once: true });
            }

            // The clip repeats for as long as the recipient stays: reaching the
            // chosen stretch's end sends the playhead back to its start rather
            // than letting the rest of the song run. Looping the element covers
            // a window that runs to the very end of the file — that wrap lands
            // on 0 and is pulled back up here.
            function keepInsideClip() {
                const at = audio.currentTime;
                if (!isFinite(at)) return;
                if (MUSIC.end !== null && at >= MUSIC.end - 0.1) {
                    try { audio.currentTime = MUSIC.start; } catch (e) {}
                } else if (at < MUSIC.start - 0.25) {
                    try { audio.currentTime = MUSIC.start; } catch (e) {}
                }
            }

            function startMusic() {
                if (started) return;
                started = true;

                const saved = readState();

                audio.muted = false;
                audio.volume = 0;

                let at = MUSIC.start;
                if (saved && isFinite(saved.t) && saved.t > MUSIC.start &&
                    (MUSIC.end === null || saved.t < MUSIC.end - 0.25)) {
                    at = saved.t;
                }
                seek(at);

                badge.hidden = false;
                requestAnimationFrame(function () { badge.classList.add('ready'); });
                // The song's name has had its moment; give the corner back.
                setTimeout(function () { badge.classList.add('tucked'); }, 5200);

                play();
            }

            if (MUSIC) {
                let lastSave = 0;

                audio.addEventListener('timeupdate', function () {
                    keepInsideClip();
                    const now = Date.now();
                    if (now - lastSave < 500) return;
                    lastSave = now;
                    writeState();
                });

                audio.addEventListener('play', showPlaying);
                audio.addEventListener('pause', showPlaying);

                window.addEventListener('pagehide', writeState);
                window.addEventListener('beforeunload', writeState);

                // A tab coming back to the foreground should not come back
                // silent — nothing on screen would let the recipient fix it.
                document.addEventListener('visibilitychange', function () {
                    if (document.visibilityState === 'visible') resume();
                });
            }

            // ── Every page the frame loads ───────────────────────────────

            function onFrameLoad() {
                const path = framePath();
                if (!path) return;

                const clean = withoutFrameFlag(path);
                if (clean && clean !== location.pathname + location.search) {
                    history.replaceState(null, '', clean);
                }

                if (!MUSIC) return;

                // The lock screen is where a run of the story begins, so it is
                // also where a previous run's saved position is dropped.
                if (clean.split('?')[0] === LOCK_PATH) {
                    clearState();
                    return;
                }

                startMusic();
                if (audio.paused) armGesture();
            }

            // The lock screen posts up as soon as the code is accepted. That
            // click is the recipient's user gesture, and starting the music on
            // it — rather than waiting for the welcome page to finish loading a
            // second and a half later — is the difference between playing and
            // being refused on browsers that expire activation across a
            // navigation. By the time the frame moves on, the song is already
            // going, which is the whole point of the shell.
            window.addEventListener('message', function (event) {
                if (event.origin !== window.location.origin) return;
                if (!event.data || event.data.type !== 'birthday-story-unlocked') return;
                if (!MUSIC) return;
                clearState();
                startMusic();
            });

            frame.addEventListener('load', onFrameLoad);

            // The frame can finish loading before this script runs — a cached
            // page, or simply a fast one — and then the load event above never
            // fires and the music never starts. Catch that case here.
            try {
                if (frame.contentDocument && frame.contentDocument.readyState === 'complete') onFrameLoad();
            } catch (e) {}
        })();
    </script>
</body>

</html>
