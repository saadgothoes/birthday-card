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
    public static function welcome(string $nextUrl): string
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
    public static function gifts(array $urls): string
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
        HTML;
    }

    /** Gifts 1 and 2 — a way back to the gift screen. */
    public static function gift(string $backUrl): string
    {
        $url = e($backUrl);

        return self::styles() . "<div class=\"story-nav\"><a href=\"{$url}\">Next →</a></div>";
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
    public static function book(string $endingUrl, string $backUrl): string
    {
        $ending = e($endingUrl);
        $back = e($backUrl);

        return self::styles()
            . "<div class=\"story-nav\">"
            . "<a href=\"{$back}\">← Gifts</a>"
            . "<a href=\"{$ending}\">One Last Thing →</a>"
            . "</div>";
    }

    /** The ending page — the story is over; only a way back is offered. */
    public static function ending(string $giftsUrl): string
    {
        $url = e($giftsUrl);

        return self::styles() . "<div class=\"story-nav\"><a href=\"{$url}\">← Back to the gifts</a></div>";
    }
}