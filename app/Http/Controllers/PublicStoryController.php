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

    /** Look a story up by its slug, or 404. */
    private function story(string $slug): BirthdayCard
    {
        return BirthdayCard::where('slug', $slug)->firstOrFail();
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
        if (! in_array($card->theme, self::LIVE_THEMES, true)) {
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
        $view = 'birthday.' . $card->theme . '-page-' . $page;

        return $design > 1 ? $view . '-' . $design : $view;
    }

    private function giftView(BirthdayCard $card, int $gift, int $design): string
    {
        return 'birthday.' . $card->theme
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
            'title' => $card->heading ?: 'A Birthday Surprise',
            'side' => $card->theme,
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

    public function lock(Request $request, string $slug)
    {
        $card = $this->story($slug);

        if (! in_array($card->theme, self::LIVE_THEMES, true)) {
            abort(404);
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
                $card->theme . '-' . $this->variant($card)
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

        if (! in_array($card->theme, self::LIVE_THEMES, true)) {
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

        $params = match ($gift) {
            1 => $this->gift1Params($data),
            2 => $this->gift2Params($data),
            3 => $card->theme === 'girl' ? $this->gift3GirlParams($data) : $this->gift3Params($data),
        };

        // Gifts 1 and 2 come back to the gift screen; the book is the last of
        // the three, so finishing it goes on to the ending page.
        $chrome = $gift === 3
            ? StoryChrome::book(route('story.ending', $card->slug), route('story.gifts', $card->slug), $this->musicClip($card))
            : StoryChrome::gift(route('story.gifts', $card->slug), $this->musicClip($card));

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

        $params = [];
        foreach (array_keys(BirthdayCardController::endingLimits($card->theme)) as $key) {
            $params[$key] = $ending[$key] ?? null;
        }

        return $this->render(
            $request,
            $card,
            $this->pageView($card, 4, $design),
            $params,
            StoryChrome::ending(route('story.gifts', $card->slug), $this->musicClip($card))
        );
    }
}
