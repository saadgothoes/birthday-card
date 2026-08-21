<?php

declare(strict_types=1);

namespace App\Support;

/**
 * The public story's navigation, added to each card template after it renders.
 *
 * The card designs are standalone HTML documents: they have no layout, no
 * shared partial, and the dashboard previews them by loading the very same
 * URLs in an iframe. Writing story navigation into them would mean editing
 * thirty-odd files and would leave Next buttons hanging in the dashboard's
 * previews, where there is nowhere to go.
 *
 * So the chrome is injected into the rendered HTML instead. Each snippet is
 * self-contained — its own styles, its own script — and it only ever adds to
 * the page: existing markup and behaviour are left alone, and where a design
 * already has a button for the job (the welcome screen's NEXT, the gift
 * screen's gift areas, the book's Replay row) the snippet wires that button up
 * rather than drawing a second one.
 */
final class StoryChrome
{
    /**
     * Where the music playhead is parked between pages.
     *
     * Each screen of the story is its own document, so the audio element is
     * destroyed on every Next — this entry is what survives the navigation.
     */
    private const MUSIC_KEY = 'birthday-story-music';

    /** Put a snippet in just before </body>, or at the end if there isn't one. */
    public static function inject(string $html, string $chrome): string
    {
        $position = strripos($html, '</body>');

        return $position === false
            ? $html . $chrome
            : substr($html, 0, $position) . $chrome . substr($html, $position);
    }

    /** Styles shared by the floating buttons the designs don't already have. */
    private static function styles(): string
    {
        return <<<'CSS'
        <style>
        .story-nav {
            position: fixed;
            right: max(18px, env(safe-area-inset-right));
            bottom: max(18px, env(safe-area-inset-bottom));
            z-index: 9999;
            display: flex;
            gap: 10px;
            align-items: center;
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
        }

        .story-nav a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-decoration: none;
            color: #fff;
            background: rgba(20, 20, 24, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.28);
            box-shadow: 0 8px 26px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .story-nav a:hover,
        .story-nav a:focus-visible {
            transform: translateY(-2px);
            background: rgba(20, 20, 24, 0.94);
        }

        @media (max-width: 560px) {
            .story-nav a {
                padding: 10px 16px;
                font-size: 13px;
            }
        }

        @media print {
            .story-nav {
                display: none;
            }
        }
        </style>
        CSS;
    }

    /**
     * Page 1 — the lock screen.
     *
     * The design ships as decoration: four empty boxes and a numpad of dead
     * buttons. This wires them into a real 4-digit entry, checked on the
     * server — the code never reaches the page, so it can't be read out of the
     * source. `✱` clears the last digit, `#` submits, and a fourth digit
     * submits on its own. The keyboard works too.
     */
    public static function lock(string $postUrl, string $csrf, ?string $photo, ?string $error, string $theme): string
    {
        $musicKey = self::MUSIC_KEY;
        $config = json_encode([
            'url' => $postUrl,
            'csrf' => $csrf,
            'photo' => $photo,
            'error' => $error,
            'theme' => $theme,
        ], JSON_UNESCAPED_SLASHES);

        return <<<HTML
        <style>
        .story-lock-error {
            margin-top: 14px;
            min-height: 20px;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #ff8a8a;
            text-align: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .story-lock-error.show {
            opacity: 1;
        }

        .story-shake {
            animation: story-shake 0.42s ease;
        }

        .story-lock-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: grid;
            place-items: center;
            padding: 24px;
            background: rgba(5, 8, 18, 0.64);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.24s ease;
        }

        .story-lock-modal-backdrop.show {
            opacity: 1;
            pointer-events: auto;
        }

        .story-lock-modal {
            width: min(100%, 330px);
            padding: 28px 24px 24px;
            border: 1px solid var(--story-modal-border);
            border-radius: 22px;
            color: var(--story-modal-text);
            background: var(--story-modal-bg);
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.42), 0 0 35px var(--story-modal-glow);
            text-align: center;
            font-family: 'DM Sans', system-ui, sans-serif;
            transform: translateY(16px) scale(.96);
            transition: transform 0.28s cubic-bezier(.22, 1, .36, 1);
        }

        .story-lock-modal-backdrop.show .story-lock-modal {
            transform: translateY(0) scale(1);
        }

        .story-lock-modal.theme-boy-1 { --story-modal-bg: linear-gradient(145deg, #351709, #160804); --story-modal-border: rgba(255, 193, 66, .55); --story-modal-text: #fff4d5; --story-modal-accent: #ffc342; --story-modal-glow: rgba(255, 154, 35, .22); }
        .story-lock-modal.theme-boy-2 { --story-modal-bg: linear-gradient(145deg, #182d52, #0b1428); --story-modal-border: rgba(119, 178, 255, .58); --story-modal-text: #eef6ff; --story-modal-accent: #8dc5ff; --story-modal-glow: rgba(80, 145, 255, .24); }
        .story-lock-modal.theme-girl-1 { --story-modal-bg: linear-gradient(145deg, #fff2f7, #f5d9e6); --story-modal-border: rgba(220, 83, 139, .38); --story-modal-text: #5a2640; --story-modal-accent: #d9558f; --story-modal-glow: rgba(217, 85, 143, .2); }
        .story-lock-modal.theme-girl-2 { --story-modal-bg: linear-gradient(145deg, #332044, #170e26); --story-modal-border: rgba(201, 155, 255, .58); --story-modal-text: #fbf4ff; --story-modal-accent: #d1aaff; --story-modal-glow: rgba(174, 112, 255, .24); }

        .story-lock-modal-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            display: grid;
            place-items: center;
            border: 2px solid var(--story-modal-accent);
            border-radius: 50%;
            color: var(--story-modal-accent);
            font-size: 28px;
            font-weight: 700;
        }

        .story-lock-modal h2 { margin: 0; font-size: 21px; }
        .story-lock-modal p { margin: 9px 0 0; font-size: 14px; line-height: 1.5; opacity: .82; }
        .story-lock-modal-close {
            margin-top: 20px;
            padding: 10px 20px;
            border: 1px solid var(--story-modal-border);
            border-radius: 999px;
            color: var(--story-modal-text);
            background: transparent;
            cursor: pointer;
            font: inherit;
        }

        .story-lock-success-icon { position: relative; border-color: var(--story-modal-accent); }
        .story-lock-spinner {
            width: 22px;
            height: 22px;
            border: 3px solid color-mix(in srgb, var(--story-modal-accent) 28%, transparent);
            border-top-color: var(--story-modal-accent);
            border-radius: 50%;
            animation: story-spin .8s linear infinite;
        }
        .story-lock-check { display: none; font-size: 30px; animation: story-check .45s cubic-bezier(.22, 1.4, .36, 1) both; }
        .story-lock-success-icon.done .story-lock-spinner { display: none; }
        .story-lock-success-icon.done .story-lock-check { display: block; }

        @keyframes story-spin { to { transform: rotate(360deg); } }
        @keyframes story-check { from { opacity: 0; transform: scale(.4) rotate(-18deg); } to { opacity: 1; transform: scale(1) rotate(0); } }

        @keyframes story-shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-7px);
            }

            40%,
            80% {
                transform: translateX(7px);
            }
        }

        .arch-photo img.story-photo,
        .oval-photo img.story-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        </style>
        <script>
        (function () {
            const CONFIG = {$config};
            // Each lock design names its own parts — .boy2-*, .girl-*, .girl2-* —
            // so the wiring matches all of them rather than one side's markup.
            const boxes = Array.from(document.querySelectorAll(
                '.boy2-code-box, .girl-code-box, .girl2-code-box'));
            const buttons = Array.from(document.querySelectorAll(
                '.boy2-numpad .boy2-btn, .girl-numpad .girl-btn, .girl2-numpad .girl2-btn'));
            if (!boxes.length) return;

            // The designs ship with placeholder digits in the boxes; clear them
            // so the recipient starts on an empty passcode.
            boxes.forEach(box => { box.textContent = ''; });

            // Some designs draw an illustration where the photo goes, so the
            // client's own picture is dropped in here. The ones that already
            // have an <img> the photo parameter fills are left alone.
            const frame = document.querySelector('.arch-photo, .oval-photo');
            if (CONFIG.photo && frame && !frame.querySelector('img')) {
                const img = document.createElement('img');
                img.className = 'story-photo';
                img.alt = '';
                img.src = CONFIG.photo;
                frame.innerHTML = '';
                frame.appendChild(img);
            }

            const message = document.createElement('div');
            message.className = 'story-lock-error';
            message.setAttribute('role', 'alert');
            const panel = boxes[0].closest('.boy2-right, .girl-right, .girl2-right')
                || boxes[0].parentElement.parentElement;
            panel.appendChild(message);

            const modalBackdrop = document.createElement('div');
            modalBackdrop.className = 'story-lock-modal-backdrop';
            modalBackdrop.innerHTML = `
                <section class="story-lock-modal" role="dialog" aria-modal="true" aria-labelledby="story-lock-modal-title">
                    <div class="story-lock-modal-icon" data-modal-icon>!</div>
                    <h2 id="story-lock-modal-title" data-modal-title></h2>
                    <p data-modal-text></p>
                    <button class="story-lock-modal-close" type="button" data-modal-close>Try again</button>
                </section>`;
            document.body.appendChild(modalBackdrop);
            const modal = modalBackdrop.querySelector('.story-lock-modal');
            modal.classList.add('theme-' + CONFIG.theme);
            const modalIcon = modalBackdrop.querySelector('[data-modal-icon]');
            const modalTitle = modalBackdrop.querySelector('[data-modal-title]');
            const modalText = modalBackdrop.querySelector('[data-modal-text]');
            const modalClose = modalBackdrop.querySelector('[data-modal-close]');

            function showModal(kind, text) {
                modalIcon.className = 'story-lock-modal-icon' + (kind === 'success' ? ' story-lock-success-icon' : '');
                modalIcon.innerHTML = kind === 'success'
                    ? '<span class="story-lock-spinner" aria-label="Checking"></span><span class="story-lock-check">✓</span>'
                    : '!';
                modalTitle.textContent = kind === 'success' ? 'Passcode correct' : 'Not quite';
                modalText.textContent = kind === 'success' ? 'Opening your special surprise…' : (text || 'Maybe it is your special date?');
                modalClose.style.display = kind === 'success' ? 'none' : '';
                modalBackdrop.classList.add('show');
            }

            function closeModal() {
                modalBackdrop.classList.remove('show');
            }

            modalClose.addEventListener('click', closeModal);
            modalBackdrop.addEventListener('click', event => {
                if (event.target === modalBackdrop) closeModal();
            });

            let code = '';
            let busy = false;

            function paint() {
                boxes.forEach((box, i) => {
                    box.textContent = code[i] ? '•' : '';
                    box.classList.toggle('active', i === Math.min(code.length, boxes.length - 1));
                });
            }

            function showError(text) {
                message.textContent = text;
                message.classList.add('show');
                const row = boxes[0].parentElement;
                row.classList.remove('story-shake');
                void row.offsetWidth;
                row.classList.add('story-shake');
            }

            function clearError() {
                message.classList.remove('show');
            }

            function submit() {
                if (busy || code.length < boxes.length) return;
                busy = true;

                const body = new FormData();
                body.append('code', code);

                fetch(CONFIG.url, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CONFIG.csrf },
                        body: body,
                    })
                    .then(res => res.json().then(data => ({ ok: res.ok, data })))
                    .then(({ ok, data }) => {
                        if (ok && data.success) {
                            // A new run of the story starts the song at the
                            // top, not wherever a previous visit left it.
                            try { sessionStorage.removeItem('{$musicKey}'); } catch (e) {}
                            // The successful PIN click is the recipient's
                            // user gesture. Let the persistent shell use it
                            // before the browser clears activation on the
                            // redirect to the welcome page.
                            try {
                                if (window.parent !== window) {
                                    window.parent.postMessage({ type: 'birthday-story-unlocked' }, '*');
                                }
                            } catch (e) {}
                            showModal('success');
                            setTimeout(() => modalIcon.classList.add('done'), 700);
                            setTimeout(() => { window.location.href = data.next; }, 1450);
                            return;
                        }
                        busy = false;
                        code = '';
                        paint();
                        showError(data.message || 'That code is not right.');
                        showModal('error', 'Maybe it is your special date?');
                    })
                    .catch(() => {
                        busy = false;
                        showError('Something went wrong. Please try again.');
                    });
            }

            function press(key) {
                if (busy) return;
                clearError();

                if (key === 'back') {
                    code = code.slice(0, -1);
                    paint();
                    return;
                }
                if (key === 'go') {
                    submit();
                    return;
                }
                if (code.length >= boxes.length) return;

                code += key;
                paint();
                if (code.length === boxes.length) setTimeout(submit, 180);
            }

            buttons.forEach(button => {
                const label = button.textContent.trim();
                const key = label === '✱' ? 'back' : (label === '#' ? 'go' : label);
                button.setAttribute('type', 'button');
                button.addEventListener('click', () => press(key));
            });

            document.addEventListener('keydown', event => {
                if (event.key >= '0' && event.key <= '9') press(event.key);
                else if (event.key === 'Backspace') press('back');
                else if (event.key === 'Enter') press('go');
            });

            paint();
            if (CONFIG.error) {
                showError(CONFIG.error);
                showModal('error', 'Maybe it is your special date?');
            }
        })();
        </script>
        HTML;
    }

    /**
     * Page 2 — the welcome screen already has a NEXT button; point it onward.
     * Each side names it differently (.bb-next / .gb-next).
     */
    public static function welcome(string $nextUrl, ?array $music = null): string
    {
        $url = json_encode($nextUrl, JSON_UNESCAPED_SLASHES);

        return self::styles() . <<<HTML
        <script>
        (function () {
            const next = {$url};
            const button = document.querySelector('.bb-next, .gb-next');
            if (button) {
                button.addEventListener('click', () => { window.location.href = next; });
            } else {
                // No NEXT in this design — fall back to the floating button.
                document.body.insertAdjacentHTML('beforeend',
                    '<div class="story-nav"><a href="' + next + '">Next →</a></div>');
            }
        })();
        </script>
        HTML . self::music($music);
    }

    /**
     * The story's background music.
     *
     * The audio element cannot survive a click on Next: every screen is a
     * standalone document, so the browser tears the page down and builds the
     * next one from scratch. What is carried across instead is the playhead —
     * the position is written to sessionStorage as the track plays and read
     * back on the next page, which resumes the same file from that offset. To
     * the recipient the song simply keeps going, however many pages they open
     * and however often they refresh.
     *
     * Three details make that hold up. The element is built in script rather
     * than written into the markup, because a parsed <audio autoplay> starts
     * at the top before any handler can seek it — and if the file is already
     * cached, its loadedmetadata fires before the handler is even attached, so
     * the seek is missed entirely and the track really does start over. It is
     * created silent and faded in only once the seek has landed, so the
     * opening bars are never heard a second time. And it loops, so a story
     * that outlasts the song never drops into silence.
     *
     * A reload also spends the page's autoplay permission, so a browser may
     * refuse the play() outright; the first tap, click or key press on the
     * page picks the music back up.
     *
     * All of that is the fallback path. In the story proper the page runs
     * inside resources/views/story/shell.blade.php, whose player is created
     * once and never torn down, so the music does not stop between pages and
     * none of this resuming is needed; this snippet stands down there. It
     * still matters for a card design opened on its own, which has to keep
     * working — the dashboard previews them exactly that way.
     *
     * @param  array{url: string, start: float, end: float|null}|null  $music
     *         the chosen track and the window of it the client kept in the
     *         dashboard's trim editor; a null end means the whole song
     */
    private static function music(?array $music): string
    {
        if (! $music || empty($music['url'])) {
            return '';
        }

        $src = json_encode($music['url'], JSON_UNESCAPED_SLASHES);
        $start = json_encode(round((float) ($music['start'] ?? 0), 2));
        $end = json_encode(isset($music['end']) ? round((float) $music['end'], 2) : null);
        $key = self::MUSIC_KEY;

        return <<<HTML
        <script>
        (function () {
            const SRC = {$src};
            // The trimmed window, in seconds. END is null for the whole song.
            const START = {$start};
            const END = {$end};
            const KEY = '{$key}';

            if (document.getElementById('storyMusic')) return;

            // Inside the story shell the parent document owns the player and
            // has done since before this page loaded, so there is nothing to
            // do here — a second element would simply double the music. This
            // player is the standalone path: a card design opened on its own,
            // outside the shell, still carries its own music.
            if (window.top !== window.self) return;

            // Where the previous page left off. The source and the window are
            // checked too, so re-trimming a card — or swapping its track —
            // doesn't drop the new clip in at the old one's position.
            let from = START;
            try {
                const saved = JSON.parse(sessionStorage.getItem(KEY) || 'null');
                if (saved && saved.src === SRC && saved.start === START && saved.end === END) {
                    const at = Number(saved.t);
                    if (isFinite(at) && at > START && (END === null || at < END - 0.25)) from = at;
                }
            } catch (e) {}

            const audio = document.createElement('audio');
            audio.id = 'storyMusic';
            audio.loop = true;
            audio.preload = 'auto';
            audio.volume = 0;
            audio.muted = false;
            audio.defaultMuted = false;
            audio.setAttribute('playsinline', '');
            audio.src = SRC;
            document.body.appendChild(audio);

            let fading = false;

            function fadeIn() {
                if (fading) return;
                fading = true;
                const step = function () {
                    audio.volume = Math.min(1, audio.volume + 0.05);
                    if (audio.volume < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            }

            // A blocked play() is not an error worth surfacing — the recipient
            // is about to touch the page anyway, and that gesture starts it.
            function resumeOnGesture() {
                const go = function () {
                    audio.play().then(fadeIn).catch(function () {});
                };
                ['pointerdown', 'touchstart', 'keydown'].forEach(function (type) {
                    document.addEventListener(type, go, { capture: true, passive: true });
                });
            }

            function play() {
                const started = audio.play();
                if (started && started.then) started.then(fadeIn).catch(resumeOnGesture);
                else fadeIn();
            }

            // The clip repeats on its own: reaching the end sends the playhead
            // back to the start rather than letting the rest of the song run.
            // The element loops as well, which covers a window that runs to
            // the very end of the file — that wrap lands on 0 and is pulled
            // back up to START here.
            function keepInsideClip() {
                const at = audio.currentTime;
                if (!isFinite(at)) return;
                if (END !== null && at >= END - 0.1) {
                    try { audio.currentTime = START; } catch (e) {}
                } else if (at < START - 0.25) {
                    try { audio.currentTime = START; } catch (e) {}
                }
            }

            function begin() {
                const duration = audio.duration;
                let at = from;
                // A window saved against a different file than the one now
                // being served would seek past the end; fall back to the top.
                if (isFinite(duration) && at > duration - 0.25) at = START;
                if (isFinite(duration) && at > duration - 0.25) at = 0;
                if (at > 0) {
                    try { audio.currentTime = at; } catch (e) {}
                }
                play();
            }

            // readyState is checked as well as the event: a cached file can
            // have its metadata ready before this runs, and then the event
            // never fires again.
            if (audio.readyState >= 1) begin();
            else audio.addEventListener('loadedmetadata', begin, { once: true });

            let lastSave = 0;

            function remember() {
                const at = audio.currentTime;
                if (!isFinite(at) || at <= 0) return;
                try {
                    sessionStorage.setItem(KEY, JSON.stringify({
                        src: SRC, start: START, end: END, t: at,
                    }));
                } catch (e) {}
            }

            audio.addEventListener('timeupdate', function () {
                keepInsideClip();
                const now = Date.now();
                if (now - lastSave < 250) return;
                lastSave = now;
                remember();
            });

            // timeupdate stops the moment the page starts unloading, so the
            // last quarter-second is caught here — pagehide covers the back /
            // forward cache, where unload never runs at all.
            window.addEventListener('pagehide', remember);
            window.addEventListener('beforeunload', remember);
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'hidden') remember();
                else if (audio.paused) audio.play().then(fadeIn).catch(function () {});
            });

            // Coming back through the browser's back button restores a paused
            // element on some browsers; start it moving again.
            window.addEventListener('pageshow', function (event) {
                if (event.persisted && audio.paused) play();
            });
        })();
        </script>
        HTML;
    }

    /**
     * Page 3 — the gift screen.
     *
     * The design's gift areas already call `openGiftPage(n)`, which pointed at
     * a URL that was never built. Redefining it keeps the loading-screen
     * flourish the design plays and sends the recipient to their own story's
     * gift instead.
     */
    public static function gifts(array $urls, ?array $music = null): string
    {
        $map = json_encode($urls, JSON_UNESCAPED_SLASHES);

        return <<<HTML
        <script>
        (function () {
            const routes = {$map};
            let opening = false;

            window.openGiftPage = function (gift) {
                if (opening || !routes[gift]) return;
                opening = true;

                const loader = document.getElementById('loadingScreen');
                if (loader) {
                    loader.classList.add('is-visible');
                    loader.setAttribute('aria-hidden', 'false');
                }
                setTimeout(() => { window.location.href = routes[gift]; }, loader ? 900 : 0);
            };
        })();
        </script>
        HTML . self::music($music);
    }

    /** Gifts 1 and 2 — a way back to the gift screen. */
    public static function gift(string $backUrl, ?array $music = null): string
    {
        $url = e($backUrl);

        return self::styles() . "<div class=\"story-nav\"><a href=\"{$url}\">Next →</a></div>" . self::music($music);
    }

    /**
     * Gift 3 — the book.
     *
     * The book's own last page carries a Replay / Close the Book row, but the
     * cover sits over that area in the stacking order once the book is shut,
     * so a third button dropped into that row is not reliably clickable. The
     * way onward lives in the floating nav instead, which is above everything
     * and works whatever state the book is in — including after the recipient
     * closes it.
     */
    public static function book(string $endingUrl, string $backUrl, ?array $music = null): string
    {
        $ending = e($endingUrl);
        $back = e($backUrl);

        return self::styles()
            . "<div class=\"story-nav\">"
            . "<a href=\"{$back}\">← Gifts</a>"
            . "<a href=\"{$ending}\">One Last Thing →</a>"
            . "</div>" . self::music($music);
    }

    /**
     * The ending page — the story is over; only a way back is offered.
     *
     * The music plays on here too. This is the last screen rather than a way
     * out, and the recipient can still walk back to the gifts, so cutting the
     * song off at the door would be the one silence in the whole story.
     */
    public static function ending(string $giftsUrl, ?array $music = null): string
    {
        $url = e($giftsUrl);

        return self::styles()
            . "<div class=\"story-nav\"><a href=\"{$url}\">← Back to the gifts</a></div>"
            . self::music($music);
    }
}
