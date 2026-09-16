<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Client\BirthdayCardController;
use App\Models\BirthdayCard;
use App\Models\MusicTrack;
use App\Support\StoryChrome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * The public story — what a recipient sees when they open the link or scan the
 * QR generated in the dashboard.
 *
 * Every page is one of the existing card templates, rendered with the values
 * the client saved. The templates already read their content off the query
 * string (`request('key', default)`), so this controller's job is to look the
 * card up by its slug, turn its stored JSON into that same parameter set, and
 * merge it into the request before rendering. That means the public story and
 * the dashboard's live preview are literally the same page with the same
 * inputs — there is no second, hardcoded copy of the story anywhere.
 *
 * Navigation is injected rather than written into the templates: the card
 * designs are standalone documents that must still render on their own (the
 * dashboard previews them that way), so the story's Next/Back chrome is added
 * to the rendered HTML by App\Support\StoryChrome instead of being baked in.
 *
 * Both sides are wired. The two themes are not the same story in different
 * colours — the girl gifts are a calendar board, a wrapped-box scene and a
 * phone full of memories where the boy's are a photo board, memory tiles and a
 * book — so the parameter mapping below branches per side where the designs
 * genuinely differ, and the ending pages are different designs entirely.
 */
class PublicStoryController extends Controller
{
    /** Sides whose public story is wired up. */
    private const LIVE_THEMES = ['boy', 'girl'];

    /** An anniversary card carries `occasion`, not a boy/girl `theme`. */
    private function isAnniversary(BirthdayCard $card): bool
    {
        return $card->occasion === 'anniversary';
    }

    /**
     * A proposal is the odd one out: it is not a five-screen story but a
     * single page. There is no code to enter, no welcome, no gifts and no
     * ending — the recipient opens the link and the whole thing happens there.
     */
    private function isProposal(BirthdayCard $card): bool
    {
        return $card->occasion === 'proposal';
    }

    /** Can this card's story be rendered at all? */
    private function isLive(BirthdayCard $card): bool
    {
        return $this->isAnniversary($card)
            || $this->isProposal($card)
            || in_array($card->theme, self::LIVE_THEMES, true);
    }

    /** Look a story up by its slug, or 404. */
    private function story(string $slug): BirthdayCard
    {
        $card = BirthdayCard::where('slug', $slug)->firstOrFail();

        if (! $card->linkIsAvailable()) {
            abort($this->unavailable($card));
        }

        return $card;
    }

    /**
     * The page a recipient gets when the link will not open.
     *
     * This used to be a bare `abort(410)`, which handed the framework's error
     * page to someone who has never seen this product and is only here because
     * a friend sent them a link. So it is a real page now — see
     * resources/views/story/unavailable.blade.php.
     *
     * Two things the status codes are carrying:
     *
     * - A **disabled** link is 403, not 410. It is switched off, not gone: the
     *   owner can turn it back on and the same address works again. 410 tells
     *   every cache and crawler the opposite.
     * - `no-store` matters for the same reason. A disabled link that a browser
     *   or an intermediary caches would keep showing this page after the owner
     *   re-enables it, and the recipient would have no way to tell.
     */
    private function unavailable(BirthdayCard $card)
    {
        // Expiry is the terminal state — a link that ran out its 15 days cannot
        // be enabled again (see CardManagerController@toggleLink), so it is
        // reported as expired even when it was also switched off.
        [$reason, $status] = match (true) {
            $card->linkIsExpired() => ['expired', 410],
            $card->linkIsDisabled() => ['disabled', 403],
            default => ['unavailable', 404],
        };

        return response()
            ->view('story.unavailable', ['reason' => $reason], $status)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /** Session key holding whether this browser has entered the right code. */
    private function unlockKey(BirthdayCard $card): string
    {
        return 'story_unlocked_' . $card->id;
    }

    private function isUnlocked(Request $request, BirthdayCard $card): bool
    {
        return (bool) $request->session()->get($this->unlockKey($card));
    }

    /**
     * Pages past the lock screen all need the same two checks, so they share
     * one guard: the story has to be one we can render, and the visitor has to
     * have entered the code.
     */
    private function guard(Request $request, BirthdayCard $card)
    {
        if (! $this->isLive($card)) {
            abort(404);
        }

        // A proposal has one page and it is the entry point, so welcome /
        // gifts / ending are not "locked" for it — they simply do not exist.
        if ($this->isProposal($card)) {
            abort(404);
        }

        if (! $this->isUnlocked($request, $card)) {
            return redirect()->route('story.lock', $card->slug);
        }

        return null;
    }

    /** A stored upload path as a URL the page can use, if it is still there. */
    private function photoUrl(?string $path): ?string
    {
        return $path ? Storage::url($path) : null;
    }

    /**
     * The song a card plays, the minute of it the client kept, and what to
     * call it on screen.
     *
     * Step 9's picker stores the window as two offsets in seconds; a card
     * saved before it existed has neither, which reads here as the whole song.
     *
     * @return array{url: string, start: float, end: float|null, title: string, artist: string|null}|null
     */
    private function musicClip(BirthdayCard $card): ?array
    {
        $music = $card->music_data ?? [];

        // Prefer the library row: it carries the artist for the player, and
        // its URL is the stream route, which answers the Range requests the
        // player needs to start a minute into the song. A card whose track has
        // since been deleted falls back to the stored path.
        $track = isset($music['track_id']) ? MusicTrack::find($music['track_id']) : null;
        $url = $track ? $track->url : $this->photoUrl($music['path'] ?? null);

        if (! $url) {
            return null;
        }

        $start = (float) ($music['trim_start'] ?? 0);
        $end = isset($music['trim_end']) ? (float) $music['trim_end'] : null;

        // The length is capped here as well as on save, so a card stored under
        // an earlier rule — when the client set both ends themselves and could
        // keep more than a minute — still plays the minute the story allows.
        if ($end !== null) {
            $end = min($end, $start + BirthdayCardController::MUSIC_CLIP_SECONDS);
        }

        return [
            'url' => $url,
            'start' => $end === null ? 0.0 : $start,
            'end' => $end,
            'title' => $track->title ?? $music['title'] ?? 'Story music',
            'artist' => $track->artist ?? null,
        ];
    }

    /** Which of the two designs a card picked for a given screen. */
    private function variant(BirthdayCard $card): int
    {
        return (int) ($card->variant ?: 1);
    }

    private function giftScreenVariant(BirthdayCard $card): int
    {
        return (int) ($card->gift_screen_variant ?: 1);
    }

    /**
     * The card templates are named `{theme}-page-{n}` for the first design and
     * `{theme}-page-{n}-{v}` for the rest — the same convention the existing
     * preview routes use.
     */
    private function pageView(BirthdayCard $card, int $page, int $design): string
    {
        $prefix = $this->isAnniversary($card) ? 'anniversary' : $card->theme;
        $view = 'birthday.' . $prefix . '-page-' . $page;

        return $design > 1 ? $view . '-' . $design : $view;
    }

    private function giftView(BirthdayCard $card, int $gift, int $design): string
    {
        $prefix = $this->isAnniversary($card) ? 'anniversary' : $card->theme;

        return 'birthday.' . $prefix
            . '-page-3-variant-' . $this->giftScreenVariant($card)
            . '-gift-' . $gift
            . '-page-' . $design;
    }

    /**
     * Is this request the story itself, or the shell that wraps it?
     *
     * Browsers mark frame navigations with Sec-Fetch-Dest, which keeps the
     * shared links clean — the recipient sees `/c/abc/gifts`, not a URL with a
     * flag on it. The shell still puts `frame=1` on the src it asks for, both
     * for browsers too old to send the header and so a request is never
     * ambiguous about which half of the pair it wants.
     */
    private function insideShell(Request $request): bool
    {
        return $request->boolean('frame') || $request->header('Sec-Fetch-Dest') === 'iframe';
    }

    /**
     * The shell: one document holding the music player, with the story in a
     * frame inside it.
     *
     * Serving this instead of the page is what makes the music survive the
     * story. Moving from the welcome screen to the gifts navigates the frame,
     * not the shell, so the <audio> element in here is never torn down and the
     * track is still the same one playing — there is nothing to resume.
     *
     * @see resources/views/story/shell.blade.php
     */
    private function shell(Request $request, BirthdayCard $card)
    {
        // A relative src, so the frame is always same-origin and same-scheme
        // as the shell around it — an absolute one built from the request can
        // come back http:// behind a proxy that doesn't forward the scheme,
        // and an https page will refuse to load it.
        $query = ['frame' => 1] + $request->query();

        return response(view('story.shell', [
            'frameSrc' => $request->getPathInfo() . '?' . http_build_query($query),
            'lockPath' => route('story.lock', $card->slug, false),
            'music' => $this->musicClip($card),
            'storageKey' => 'story-music:' . $card->slug,
            'title' => $card->heading ?: $this->shellTitle($card),
            'side' => $card->occasion === 'proposal' || $this->isAnniversary($card)
                ? $card->occasion
                : $card->theme,
        ])->render());
    }

    /**
     * Render a card template with the client's saved values, then add the
     * story's own navigation to it.
     *
     * A request that isn't already inside the shell gets the shell instead;
     * the frame it opens comes straight back here for the page itself.
     *
     * @param  array<string, mixed>  $params  merged into the request, which is
     *                                        where the templates read from
     * @param  string  $chrome  the navigation snippet to inject
     */
    private function render(Request $request, BirthdayCard $card, string $view, array $params, string $chrome = '')
    {
        if (! $this->insideShell($request)) {
            return $this->shell($request, $card);
        }

        $request->merge(array_filter($params, fn($value) => $value !== null && $value !== ''));

        $html = view($view)->render();

        if ($chrome !== '') {
            $html = StoryChrome::inject($html, $chrome);
        }

        return response($html);
    }

    // ── Page 1 — the lock screen ────────────────────────────────────────

    /** What the shell's tab and badge call this card. */
    private function shellTitle(BirthdayCard $card): string
    {
        return match ($card->occasion) {
            'anniversary' => 'An Anniversary',
            'proposal' => 'A Question For You',
            default => 'A Birthday Surprise',
        };
    }

    public function lock(Request $request, string $slug)
    {
        $card = $this->story($slug);

        if (! $this->isLive($card)) {
            abort(404);
        }

        // A proposal card's link opens the proposal itself. There is nothing
        // to unlock, so this route — the one the QR encodes — renders the one
        // page the card is, with the client's words and photos in it.
        if ($this->isProposal($card)) {
            return $this->render(
                $request,
                $card,
                $this->proposalView($card),
                $this->proposalParams($card),
                StoryChrome::proposal($this->musicClip($card))
            );
        }

        // Someone who already entered the code shouldn't have to do it again.
        if ($this->isUnlocked($request, $card)) {
            return redirect()->route('story.welcome', $card->slug);
        }

        return $this->render(
            $request,
            $card,
            $this->pageView($card, 1, $this->variant($card)),
            ['photo' => $this->photoUrl($card->profile_image_path)],
            StoryChrome::lock(
                route('story.unlock', $card->slug),
                csrf_token(),
                $this->photoUrl($card->profile_image_path),
                $request->session()->pull('story_lock_error'),
                ($this->isAnniversary($card) ? 'anniversary' : $card->theme) . '-' . $this->variant($card)
            )
        );
    }

    /**
     * Check the entered code. It is compared on the server, so the code itself
     * never reaches the page — a recipient can't read it out of the source.
     */
    public function unlock(Request $request, string $slug)
    {
        $card = $this->story($slug);

        if (! $this->isLive($card)) {
            abort(404);
        }

        $entered = preg_replace('/\D/', '', (string) $request->input('code'));
        $expected = preg_replace('/\D/', '', (string) $card->lock_code);

        if ($expected !== '' && hash_equals($expected, (string) $entered)) {
            $request->session()->put($this->unlockKey($card), true);

            return $request->expectsJson()
                ? response()->json(['success' => true, 'next' => route('story.welcome', $card->slug)])
                : redirect()->route('story.welcome', $card->slug);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'That code is not right.'], 422);
        }

        return back()->with('story_lock_error', 'That code is not right.');
    }

    // ── Page 2 — the welcome screen ─────────────────────────────────────

    public function welcome(Request $request, string $slug)
    {
        $card = $this->story($slug);
        if ($redirect = $this->guard($request, $card)) {
            return $redirect;
        }

        return $this->render(
            $request,
            $card,
            $this->pageView($card, 2, $this->variant($card)),
            [
                'heading' => $card->heading,
                'message' => $card->welcome_message,
            ],
            StoryChrome::welcome(route('story.gifts', $card->slug), $this->musicClip($card))
        );
    }

    // ── Page 3 — the gift selection screen ──────────────────────────────

    public function gifts(Request $request, string $slug)
    {
        $card = $this->story($slug);
        if ($redirect = $this->guard($request, $card)) {
            return $redirect;
        }

        return $this->render(
            $request,
            $card,
            $this->pageView($card, 3, $this->giftScreenVariant($card)),
            [],
            StoryChrome::gifts([
                1 => route('story.gift', [$card->slug, 1]),
                2 => route('story.gift', [$card->slug, 2]),
                3 => route('story.gift', [$card->slug, 3]),
            ], $this->musicClip($card))
        );
    }

    // ── The three gifts ─────────────────────────────────────────────────

    public function gift(Request $request, string $slug, int $gift)
    {
        $card = $this->story($slug);
        if ($redirect = $this->guard($request, $card)) {
            return $redirect;
        }

        if (! in_array($gift, [1, 2, 3], true)) {
            abort(404);
        }

        $data = $card->{'gift' . $gift . '_data'} ?? [];
        $design = (int) ($data['theme'] ?? 1);
        if ($design < 1 || $design > 4) {
            $design = 1;
        }

        if ($this->isAnniversary($card)) {
            $params = match ($gift) {
                1 => $this->annivGift1Params($data),
                2 => $this->annivGift2Params($data),
                3 => $this->annivGift3Params($data),
            };
        } else {
            $params = match ($gift) {
                1 => $this->gift1Params($data),
                2 => $this->gift2Params($data),
                3 => $card->theme === 'girl' ? $this->gift3GirlParams($data) : $this->gift3Params($data),
            };
        }

        // Gifts 1 and 2 come back to the gift screen; the book is the last of
        // the three, so finishing it goes on to the ending page.
        //
        // Gift 2 deals out a stack of cards on the anniversary side, so its way
        // back is held until the last one is reached — otherwise the story's
        // Next sits alongside the gift's own and the two get confused. Gift 1
        // is a single page with nothing to finish.
        $chrome = $gift === 3
            ? StoryChrome::book(route('story.ending', $card->slug), route('story.gifts', $card->slug), $this->musicClip($card))
            : StoryChrome::gift(route('story.gifts', $card->slug), $this->musicClip($card), $gift === 2);

        return $this->render($request, $card, $this->giftView($card, $gift, $design), $params, $chrome);
    }

    /**
     * Gift 1 — three photos on both sides; the girl design adds the calendar
     * it marks and the note beside it.
     */
    private function gift1Params(array $data): array
    {
        $params = [];
        foreach (array_values($data['photos'] ?? []) as $i => $path) {
            $params['photo' . ($i + 1)] = $this->photoUrl($path);
        }

        $params['message'] = $data['message'] ?? null;

        return $params + BirthdayCardController::girlCalendarParams($data['cal_date'] ?? null);
    }

    /**
     * Gift 2 — the memory tiles.
     *
     * `cal_date` is stored whole so the dashboard's date picker can be
     * restored, and the day-of-month is derived here for the calendar's heart
     * marker — the same single value the dashboard preview passes, so the
     * public page matches what the client was looking at.
     */
    private function gift2Params(array $data): array
    {
        $params = [];
        foreach (array_values($data['photos'] ?? []) as $i => $path) {
            $params['photo' . ($i + 1)] = $this->photoUrl($path);
        }

        foreach (['name_first', 'name_second', 'message', 'signed'] as $key) {
            $params[$key] = $data[$key] ?? null;
        }

        if (! empty($data['cal_date'])) {
            $params['cal_day'] = (string) date('j', strtotime($data['cal_date']));
        }

        // Girl design only — the wrapped box's two lines and the polaroid captions.
        foreach (array_keys(BirthdayCardController::GIFT2_GIRL_LIMITS) as $key) {
            $params[$key] = $data[$key] ?? null;
        }

        return $params;
    }

    /**
     * Gift 3 — the boy's "Our Story" book: five photos, every page's text, the
     * special dates, and the state of the future-dreams checklist.
     */
    private function gift3Params(array $data): array
    {
        $params = [];
        foreach (array_values($data['photos'] ?? []) as $i => $path) {
            $params['photo' . ($i + 1)] = $this->photoUrl($path);
        }

        foreach (BirthdayCardController::gift3TextKeys() as $key) {
            $params[$key] = $data[$key] ?? null;
        }
        foreach (BirthdayCardController::GIFT3_DATE_KEYS as $key) {
            $params[$key] = $data[$key] ?? null;
        }
        // The tick states are booleans, so they can't go through the
        // empty-value filter in render() — an unticked box is a real value.
        foreach (BirthdayCardController::GIFT3_FLAG_KEYS as $key) {
            $params[$key] = ! empty($data[$key]) ? '1' : '0';
        }

        if (! empty($data['dream_count'])) {
            $params['dream_count'] = (string) $data['dream_count'];
        }

        return $params;
    }

    /**
     * Gift 3 — the girl's camera roll: the same five image slots hold three
     * photo cards and the two video posters, plus the clips themselves and
     * every card's text.
     */
    private function gift3GirlParams(array $data): array
    {
        $params = [];

        $photos = array_values($data['photos'] ?? []);
        foreach (BirthdayCardController::GIFT3_GIRL_PHOTO_KEYS as $i => $key) {
            $params[$key] = $this->photoUrl($photos[$i] ?? null);
        }

        $videos = array_values($data['videos'] ?? []);
        foreach (BirthdayCardController::GIFT3_GIRL_VIDEO_KEYS as $i => $key) {
            $params[$key] = $this->photoUrl($videos[$i] ?? null);
        }

        foreach (BirthdayCardController::gift3GirlTextKeys() as $key) {
            $params[$key] = $data[$key] ?? null;
        }

        return $params;
    }

    // ── Anniversary gift params ────────────────────────────────────────
    // The anniversary gifts share a couple of shapes: names + a date that
    // prints as a month name + day, and the letter/signature.
    private function annivCoupleParams(array $data): array
    {
        $p = [
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'years' => isset($data['years']) ? (string) $data['years'] : null,
            'message' => $data['message'] ?? null,
            'signed' => $data['signed'] ?? null,
        ];
        if (! empty($data['cal_date']) && ($ts = strtotime($data['cal_date']))) {
            $p['cal_month'] = date('F', $ts);
            $p['cal_day'] = (string) date('j', $ts);
        }
        return $p;
    }

    // Gift 1 — Keepsake: 3 photos + couple + date + years + letter.
    private function annivGift1Params(array $data): array
    {
        $p = $this->annivCoupleParams($data);
        foreach (array_values($data['photos'] ?? []) as $i => $path) {
            $p['photo' . ($i + 1)] = $this->photoUrl($path);
        }
        return $p;
    }

    // Gift 2 — Scratch cards: couple + letter + the memory JSON (its photo
    // URLs were already resolved to /storage paths on save).
    private function annivGift2Params(array $data): array
    {
        $p = [
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'message' => $data['message'] ?? null,
            'signed' => $data['signed'] ?? null,
        ];
        if (! empty($data['memories'])) {
            $p['memories'] = json_encode(array_values($data['memories']));
        }
        return $p;
    }

    // Gift 3 — Pop-up Book: 3 photos + couple + date + years + two lines + letter.
    private function annivGift3Params(array $data): array
    {
        $p = $this->annivCoupleParams($data);
        foreach (array_values($data['photos'] ?? []) as $i => $path) {
            $p['photo' . ($i + 1)] = $this->photoUrl($path);
        }
        $p['line1'] = $data['line1'] ?? null;
        $p['line2'] = $data['line2'] ?? null;
        return $p;
    }

    // ── The proposal — one page, one design, one theme ──────────────────

    /**
     * `variant` is the design and `gift_screen_variant` its colour theme, the
     * same two numbers the dashboard's preview URL is built from — so the page
     * a client approved and the page a recipient opens are the same file.
     */
    private function proposalView(BirthdayCard $card): string
    {
        $design = (int) ($card->variant ?: 1);
        $theme = (int) ($card->gift_screen_variant ?: 1);

        if ($design < 1 || $design > 4) {
            $design = 1;
        }
        if ($theme < 1 || $theme > 4) {
            $theme = 1;
        }

        return 'birthday.proposal-design-' . $design . '-theme-' . $theme;
    }

    /**
     * Everything the client typed, as the query parameters the design already
     * reads. Only the chosen design's own fields were stored, so this hands
     * over whatever is there rather than a fixed list — a Countdown card has
     * no letter and a Box card has no wedding date.
     */
    private function proposalParams(BirthdayCard $card): array
    {
        $data = $card->gift1_data ?? [];
        $params = [];

        foreach ($data as $key => $value) {
            // `design` / `theme` are already in the view name, and `photos`
            // is a set of stored paths rather than a page parameter.
            if (in_array($key, ['design', 'theme', 'photos'], true)) {
                continue;
            }
            $params[$key] = is_scalar($value) ? (string) $value : null;
        }

        foreach (BirthdayCardController::PROPOSAL_PHOTO_KEYS as $key) {
            $params[$key] = $this->photoUrl($data['photos'][$key] ?? null);
        }

        return $params;
    }

    // ── The ending page ─────────────────────────────────────────────────

    public function ending(Request $request, string $slug)
    {
        $card = $this->story($slug);
        if ($redirect = $this->guard($request, $card)) {
            return $redirect;
        }

        $ending = $card->ending_data ?? [];
        $design = (int) ($ending['theme'] ?? 1);
        if ($design < 1 || $design > 4) {
            $design = 1;
        }

        if ($this->isAnniversary($card)) {
            $params = [
                'name_first' => $ending['name_first'] ?? null,
                'name_second' => $ending['name_second'] ?? null,
                'years' => isset($ending['years']) ? (string) $ending['years'] : null,
                'message' => $ending['message'] ?? null,
                'signed' => $ending['signed'] ?? null,
            ];
        } else {
            $params = [];
            foreach (array_keys(BirthdayCardController::endingLimits($card->theme)) as $key) {
                $params[$key] = $ending[$key] ?? null;
            }
        }

        return $this->render(
            $request,
            $card,
            $this->pageView($card, 4, $design),
            $params,
            StoryChrome::ending($this->musicClip($card))
        );
    }
}
