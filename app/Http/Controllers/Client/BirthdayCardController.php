<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Models\MusicTrack;
use App\Support\QrRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BirthdayCardController extends Controller
{
    /**
     * The card every wizard step writes to.
     *
     * Cards used to be resolved as "this client's latest unpublished card",
     * which meant starting a new card silently reopened the previous one and
     * every step wrote over it. A card is now addressed explicitly:
     *
     *   1. the `X-Card-Id` header the dashboard attaches to each save,
     *   2. the card the dashboard was last opened with, held in the session,
     *   3. only as a last resort, the newest draft — or a brand new card.
     *
     * Every lookup is scoped to the signed-in client, so one client can never
     * reach another's card by guessing an id.
     */
    protected function currentDraft(): BirthdayCard
    {
        $card = $this->resolveCard(request());

        if (! $card) {
            $card = $this->createCardForCurrentUser();
        }

        abort_if($card->is_published, 403, 'Generated cards are read-only. Create a new card to make another version.');

        return $card;
    }

    /** Locate the card this request is for, without creating one. */
    protected function resolveCard(?Request $request = null): ?BirthdayCard
    {
        $request ??= request();

        foreach ([$request->header('X-Card-Id'), $request->input('card_id'), session('active_card_id')] as $candidate) {
            if (! $candidate) {
                continue;
            }

            $card = BirthdayCard::where('user_id', Auth::id())
                ->whereKey($candidate)
                ->first();

            if ($card) {
                return $card;
            }
        }

        return BirthdayCard::where('user_id', Auth::id())
            ->where('is_published', false)
            ->latest()
            ->first();
    }

    /**
     * Create an empty card for the signed-in client, enforcing the plan's
     * card limit. Nothing is copied from any earlier card — a new card starts
     * genuinely blank.
     */
    public static function createCardForCurrentUser(): BirthdayCard
    {
        $user = Auth::user();

        abort_if(! $user->canCreateCard(), 403, 'You have reached the card limit for your plan.');

        return BirthdayCard::create([
            'user_id' => $user->id,
            'current_step' => 1,
            'is_published' => false,
            'last_opened_at' => now(),
        ]);
    }

    /**
     * Browsers submit textareas with CRLF. Normalising on the way in keeps
     * one convention in the stored JSON, so the templates' line handling and
     * the line counts here agree with what the client actually typed.
     */
    protected function normaliseNewlines(?string $value): ?string
    {
        return $value === null ? null : str_replace(["\r\n", "\r"], "\n", $value);
    }

    // Occasion — the very first choice, before Step 1. "birthday" leaves the
    // existing boy/girl wizard exactly as it is; "anniversary" swaps in the
    // anniversary theme picker, and "proposal" the four-step proposal wizard.
    // Does not touch current_step — it sits before it.
    public function saveOccasion(Request $request)
    {
        $data = $request->validate([
            'occasion' => 'required|in:birthday,anniversary,proposal',
        ]);

        $card = $this->currentDraft();
        $card->occasion = $data['occasion'];
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    // ─── Anniversary wizard ──────────────────────────────────────────────
    // Its own endpoints so the boy/girl steps stay exactly as they are. They
    // reuse the same generic columns (variant, lock_code, profile_image_path,
    // heading, welcome_message) and `current_step` — a card is one occasion or
    // the other, and `occasion` decides which flow the dashboard renders.

    // Anniversary step 1 — the chosen design (variant 1-4).
    public function saveAnniversaryTheme(Request $request)
    {
        $data = $request->validate([
            'variant' => 'required|integer|in:1,2,3,4',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';
        $card->variant = $data['variant'];
        $card->current_step = max($card->current_step, 2);
        $card->save();

        return response()->json(['success' => true, 'card_id' => $card->id]);
    }

    // Anniversary step 2 — lock screen: 4-digit code + framed photo.
    public function saveAnniversaryLock(Request $request)
    {
        $data = $request->validate([
            'lock_code' => 'required|digits:4',
            'photo' => 'nullable|image|max:5120',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';
        $card->lock_code = $data['lock_code'];

        if ($request->hasFile('photo')) {
            if ($card->profile_image_path) {
                Storage::disk('public')->delete($card->profile_image_path);
            }
            $card->profile_image_path = $request->file('photo')->store('birthday-cards/profile', 'public');
        }

        $card->current_step = max($card->current_step, 3);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'profile_image_url' => $card->profile_image_path ? Storage::url($card->profile_image_path) : null,
        ]);
    }

    // Anniversary step 3 — welcome screen: heading + message.
    public function saveAnniversaryWelcome(Request $request)
    {
        $data = $request->validate([
            'heading' => 'nullable|string|max:' . self::WELCOME_LIMITS['heading'],
            'message' => 'nullable|string|max:' . self::WELCOME_LIMITS['message'],
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';
        $card->heading = $data['heading'] ?? null;
        $card->welcome_message = $this->normaliseNewlines($data['message'] ?? null);
        $card->current_step = max($card->current_step, 4);
        $card->save();

        return response()->json(['success' => true, 'card_id' => $card->id]);
    }

    // Anniversary step 4 — which of the chosen colour family's two designs the
    // gift-selection screen uses.
    public function saveAnniversaryGiftScreen(Request $request)
    {
        $data = $request->validate([
            'gift_screen_variant' => 'required|integer|in:1,2,3,4',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';
        $card->gift_screen_variant = $data['gift_screen_variant'];
        $card->current_step = max($card->current_step, 5);
        $card->save();

        return response()->json(['success' => true, 'card_id' => $card->id]);
    }

    // Anniversary step 5 — Gift 1 ("Keepsake"): design + 3 photos + couple names,
    // date, years and the framed letter. Stored in gift1_data with anniversary
    // keys (a card is one occasion or the other, so no clash with the boy/girl shape).
    public function saveAnniversaryGift1(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'nullable|image|max:5120',
            'name_first' => 'nullable|string|max:20',
            'name_second' => 'nullable|string|max:20',
            'cal_date' => 'nullable|date',
            'years' => 'nullable|integer|min:1|max:99',
            'message' => 'nullable|string|max:300',
            'signed' => 'nullable|string|max:30',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';

        $existing = $card->gift1_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null];
        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift1', 'public');
        }

        $card->gift1_data = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'cal_date' => $data['cal_date'] ?? null,
            'years' => $data['years'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
        ];
        $card->current_step = max($card->current_step, 6);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    // Anniversary step 6 — Gift 2 ("Scratch to reveal"): design + names + up to
    // 4 memory cards + closing letter. Stored in gift2_data.
    public function saveAnniversaryGift2(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'name_first' => 'nullable|string|max:20',
            'name_second' => 'nullable|string|max:20',
            'message' => 'nullable|string|max:300',
            'signed' => 'nullable|string|max:30',
            'memories' => 'nullable|array|max:6',
            'memories.*.date' => 'nullable|string|max:40',
            'memories.*.title' => 'nullable|string|max:40',
            'memories.*.text' => 'nullable|string|max:120',
            'photos' => 'nullable|array|max:6',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';

        $existing = $card->gift2_data ?? [];
        $photos = $existing['photos'] ?? array_fill(0, 6, null);
        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift2', 'public');
        }

        $memories = [];
        foreach ($data['memories'] ?? [] as $i => $m) {
            $memories[] = [
                'date' => $m['date'] ?? null,
                'title' => $m['title'] ?? null,
                'text' => $this->normaliseNewlines($m['text'] ?? null),
                'photo' => $photos[$i] ? Storage::url($photos[$i]) : null,
            ];
        }

        $card->gift2_data = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
            'memories' => $memories,
        ];
        $card->current_step = max($card->current_step, 7);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    // Anniversary step 7 — Gift 3 ("Pop-up Book"): design + 3 photos + names,
    // date, years, the two spread lines and the letter. Stored in gift3_data.
    public function saveAnniversaryGift3(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'nullable|image|max:5120',
            'name_first' => 'nullable|string|max:20',
            'name_second' => 'nullable|string|max:20',
            'cal_date' => 'nullable|date',
            'years' => 'nullable|integer|min:1|max:99',
            'line1' => 'nullable|string|max:60',
            'line2' => 'nullable|string|max:60',
            'message' => 'nullable|string|max:300',
            'signed' => 'nullable|string|max:30',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';

        $existing = $card->gift3_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null];
        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift3', 'public');
        }

        $card->gift3_data = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'cal_date' => $data['cal_date'] ?? null,
            'years' => $data['years'] ?? null,
            'line1' => $data['line1'] ?? null,
            'line2' => $data['line2'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
        ];
        $card->current_step = max($card->current_step, 8);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    // Anniversary step 8 — Ending page ("Blow out the candles"): design + names,
    // years, closing message + signature. Stored in ending_data.
    public function saveAnniversaryEnding(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'name_first' => 'nullable|string|max:20',
            'name_second' => 'nullable|string|max:20',
            'years' => 'nullable|integer|min:1|max:99',
            'message' => 'nullable|string|max:240',
            'signed' => 'nullable|string|max:30',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'anniversary';
        $card->ending_data = [
            'theme' => (int) $data['theme'],
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'years' => $data['years'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
        ];
        $card->current_step = max($card->current_step, 9);
        $card->save();

        return response()->json(['success' => true, 'card_id' => $card->id]);
    }


    // ─── Proposal wizard ─────────────────────────────────────────────────
    // A proposal is not a five-screen story like a birthday or an anniversary
    // card — it is one page that the recipient opens, plays through, and
    // answers. So it gets a four-step wizard of its own (design & theme →
    // words & photos → music → link) and the same generic columns everything
    // else uses:
    //
    //     variant              which of the four designs
    //     gift_screen_variant  which of that design's four colour themes
    //     gift1_data           every word and photo on the page
    //     music_data / qr_data the shared music + QR steps, unchanged
    //
    // The registry below is the single source of truth for what those numbers
    // mean: the dashboard renders its design cards, theme swatches and per-
    // design field list from it, and the endpoints validate against it, so a
    // fifth design or a fifth theme is one entry here rather than an edit in
    // four places.

    /**
     * The four proposal designs, each with four colour themes.
     *
     * `fields` is what the client is asked for on that design — the wizard
     * shows exactly these, and nothing that design would ignore. `photos` is
     * the same for image slots. Two of every design's themes are the soft
     * side and two the bold side (`side`), which is the only grouping the
     * dashboard shows.
     *
     * `beats` is the five phases the design plays, in order — the invitation,
     * the opening motion, what it reveals, the question, and the answer. The
     * design cards light each one up as the looping demo reaches it, so the
     * five are a contract with `_proposal_demo.blade.php`, not just a blurb.
     *
     * `defaults` is the sample wording. It is read in *two* places — the page
     * itself falls back to it when a parameter is missing, and the wizard
     * pre-fills its empty boxes from it — so a client is never handed a blank
     * form, and what they see in the preview before typing is exactly what
     * they would get if they typed nothing at all.
     */
    public const PROPOSAL_DESIGNS = [
        1 => [
            'name' => 'The Last Message',
            'mood' => 'Modern · the way you actually talk',
            'blurb' => 'A chat thread that types itself out, message by message, in the language you two already use — and the last message is the question.',
            'beats' => ['Tap to open', 'Typing…', 'The messages land', 'The question', 'The thread turns to hearts'],
            'fields' => ['heading', 'tap_label', 'chat_text', 'question', 'yes_label', 'no_label', 'yes_heading', 'letter_text', 'closing_line', 'signed'],
            'photos' => ['couple_photo', 'ring_photo'],
            'defaults' => [
                'to_name' => 'Ayesha',
                'from_name' => 'Bilal',
                'heading' => 'Us',
                'tap_label' => 'Tap to open',
                'chat_text' => "hey. are you free for a second\n"
                    . "i have been rewriting this all day\n"
                    . "so i am just going to say it\n"
                    . 'it was always going to be you',
                'question' => 'Will you marry me?',
                'yes_label' => 'Yes 💍',
                'no_label' => 'No',
                'yes_heading' => 'she said yes 🥹',
                'letter_text' => "i have typed this out about forty times.\n"
                    . "every version said the same thing.\n"
                    . "you are the person i tell everything to.\n"
                    . "good news, stupid news, all of it.\n"
                    . 'so: from here, all of it.',
                'closing_line' => 'Pinned to the top of the chat, and to the rest of my life.',
                'signed' => '— always yours',
            ],
            'themes' => [
                1 => ['name' => 'Paper',     'side' => 'soft', 'swatch' => '#efe9df', 'accent' => '#b5654a'],
                2 => ['name' => 'Bubblegum', 'side' => 'soft', 'swatch' => '#ffd9e6', 'accent' => '#e0507f'],
                3 => ['name' => 'Night Mode','side' => 'bold', 'swatch' => '#15161a', 'accent' => '#8b9cff'],
                4 => ['name' => 'Matcha',    'side' => 'bold', 'swatch' => '#14302a', 'accent' => '#9fe0b4'],
            ],
        ],
        2 => [
            'name' => 'Scratch the Foil',
            'mood' => 'Tactile · they make it happen',
            'blurb' => 'A foil card with something under it. They scratch it away with their own finger, the dust falls, and the ring and the question are what was underneath.',
            'beats' => ['A foil card', 'Scratching it away', 'The foil falls', 'The question', 'Gold dust, then the keepsake'],
            'fields' => ['heading', 'tap_label', 'question', 'yes_label', 'no_label', 'yes_heading', 'letter_text', 'closing_line', 'signed'],
            'photos' => ['ring_photo'],
            'defaults' => [
                'to_name' => 'Ayesha',
                'from_name' => 'Bilal',
                'heading' => 'One card. One question.',
                'tap_label' => 'Scratch here',
                'question' => 'Will you marry me?',
                'yes_label' => 'Yes 💍',
                'no_label' => 'No',
                'yes_heading' => 'Yes.',
                'letter_text' => "Under the shine, this is the part I meant.\n"
                    . "I am no good at speeches, so here is the short one.\n"
                    . "I want the ordinary days with you.\n"
                    . "The ones nobody takes a photo of.\n"
                    . 'All of them, if you will let me.',
                'closing_line' => 'Best odds I have ever had.',
                'signed' => '— always yours',
            ],
            'themes' => [
                1 => ['name' => 'Gold on Cream', 'side' => 'soft', 'swatch' => '#e9dcc3', 'accent' => '#a8813c'],
                2 => ['name' => 'Rose Foil',     'side' => 'soft', 'swatch' => '#f2d9dc', 'accent' => '#c06078'],
                3 => ['name' => 'Holo Black',    'side' => 'bold', 'swatch' => '#141419', 'accent' => '#9ad7ff'],
                4 => ['name' => 'Emerald Foil',  'side' => 'bold', 'swatch' => '#123b31', 'accent' => '#d8b262'],
            ],
        ],
        3 => [
            'name' => 'Written in the Stars',
            'mood' => 'Cinematic · quiet and huge',
            'blurb' => 'A night sky with seven stars brighter than the rest. They join themselves one at a time into a ring, the sky settles, and the question is written underneath it.',
            'beats' => ['Look up', 'The stars join', 'The constellation holds', 'The question', 'A meteor shower'],
            'fields' => ['heading', 'tap_label', 'question', 'yes_label', 'no_label', 'yes_heading', 'letter_text', 'closing_line', 'signed'],
            'photos' => ['ring_photo'],
            'defaults' => [
                'to_name' => 'Ayesha',
                'from_name' => 'Bilal',
                'heading' => 'Look up',
                'tap_label' => 'Tap a star',
                'question' => 'Will you marry me?',
                'yes_label' => 'Yes 💍',
                'no_label' => 'No',
                'yes_heading' => 'It was always yes',
                'letter_text' => "I used to think these things were written somewhere.\n"
                    . "I do not think that any more.\n"
                    . "I think you choose them, out loud.\n"
                    . "So here is mine, chosen:\n"
                    . 'you, and the long ordinary rest of it.',
                'closing_line' => 'Some things are decided long before anyone asks.',
                'signed' => '— always yours',
            ],
            'themes' => [
                1 => ['name' => 'Deep Indigo', 'side' => 'bold', 'swatch' => '#101733', 'accent' => '#ffe9a8'],
                2 => ['name' => 'Nebula Rose', 'side' => 'soft', 'swatch' => '#2a1030', 'accent' => '#ffbcd6'],
                3 => ['name' => 'Aurora',      'side' => 'soft', 'swatch' => '#08202a', 'accent' => '#8ff0de'],
                4 => ['name' => 'Obsidian',    'side' => 'bold', 'swatch' => '#0a0a0c', 'accent' => '#e8e6e1'],
            ],
        ],
        4 => [
            'name' => 'The Roll',
            'mood' => 'Memory · your photos, your story',
            'blurb' => 'A stack of polaroids, each one captioned. They flick through them, and the last one develops in front of them into the question.',
            'beats' => ['A stack of photos', 'Flicking through', 'The last one develops', 'The question', 'The whole roll flies back'],
            'fields' => ['heading', 'tap_label', 'caption_text', 'question', 'yes_label', 'no_label', 'yes_heading', 'letter_text', 'closing_line', 'signed'],
            'photos' => ['photo_1', 'photo_2', 'photo_3', 'ring_photo'],
            'defaults' => [
                'to_name' => 'Ayesha',
                'from_name' => 'Bilal',
                'heading' => 'Us, in order',
                'tap_label' => 'Swipe the photos',
                'caption_text' => "the day we met\n"
                    . "every day after that\n"
                    . 'and this one',
                'question' => 'Will you marry me?',
                'yes_label' => 'Yes 💍',
                'no_label' => 'No',
                'yes_heading' => 'Roll one, frame one',
                'letter_text' => "Turn over any photo we have ever taken.\n"
                    . "The same two people, getting happier.\n"
                    . "I want a whole roll of them.\n"
                    . "Then another roll. Then another.\n"
                    . 'Every frame, if you are in it.',
                'closing_line' => 'Roll one of however many we get.',
                'signed' => '— always yours',
            ],
            'themes' => [
                1 => ['name' => 'Film Cream', 'side' => 'soft', 'swatch' => '#efe7db', 'accent' => '#c0654e'],
                2 => ['name' => 'Sunwash',    'side' => 'soft', 'swatch' => '#ffe6cf', 'accent' => '#e57a52'],
                3 => ['name' => 'Darkroom',   'side' => 'bold', 'swatch' => '#17181b', 'accent' => '#f0c05a'],
                4 => ['name' => 'Cobalt',     'side' => 'bold', 'swatch' => '#16305e', 'accent' => '#ffd66b'],
            ],
        ],
    ];

    /**
     * How long every proposal text field may be.
     *
     * The designs are typeset to these: a question is one line at a display
     * size, a closing line is two, and only the Design 1 letter is a
     * paragraph. Anything longer would wrap out of the composition, so the
     * limit is enforced on the way in rather than trimmed on the way out.
     */
    public const PROPOSAL_LIMITS = [
        'to_name' => 24,
        'from_name' => 24,
        'heading' => 32,
        'tap_label' => 32,
        'chat_text' => 300,
        'caption_text' => 200,
        'letter_text' => 420,
        'question' => 60,
        'yes_label' => 20,
        'no_label' => 20,
        'yes_heading' => 44,
        'closing_line' => 160,
        'signed' => 30,
    ];

    /** Every image slot any proposal design offers. */
    public const PROPOSAL_PHOTO_KEYS = ['ring_photo', 'couple_photo', 'photo_1', 'photo_2', 'photo_3'];

    /**
     * The fields where the *shape* matters as much as the length.
     *
     * Each line of these becomes one object on the page — a chat bubble, a
     * polaroid caption — so the page has room for exactly this many, and the
     * limit is enforced on the way in rather than dropped on the way out.
     */
    public const PROPOSAL_MULTILINE = [
        'chat_text' => 5,
        'caption_text' => 4,
        'letter_text' => 6,
    ];

    /** One design's definition, design 1 by default. */
    public static function proposalDesign(?int $design): array
    {
        return self::PROPOSAL_DESIGNS[$design] ?? self::PROPOSAL_DESIGNS[1];
    }

    /**
     * A design's sample wording.
     *
     * The design partials read this for their own `request(...)` fallbacks and
     * the wizard pre-fills from it, so there is exactly one copy of every
     * default sentence in the codebase.
     */
    public static function proposalDefaults(?int $design): array
    {
        return self::proposalDesign($design)['defaults'];
    }

    /** The text fields a given design actually uses. */
    public static function proposalTextKeys(?int $design): array
    {
        return array_values(array_intersect(
            self::proposalDesign($design)['fields'],
            array_keys(self::PROPOSAL_LIMITS)
        ));
    }

    // Proposal step 1 — which design, and which of its four colour themes.
    // Both live on the card itself (`variant` / `gift_screen_variant`) rather
    // than in the JSON, so the preview URL can be built without unpacking it.
    public function saveProposalDesign(Request $request)
    {
        $data = $request->validate([
            'design' => 'required|integer|in:1,2,3,4',
            'theme' => 'required|integer|in:1,2,3,4',
        ]);

        $card = $this->currentDraft();
        $card->occasion = 'proposal';
        $card->variant = (int) $data['design'];
        $card->gift_screen_variant = (int) $data['theme'];
        $card->current_step = max($card->current_step, 2);
        $card->save();

        return response()->json(['success' => true, 'card_id' => $card->id]);
    }

    // Proposal step 2 — every word and photo on the page, stored in gift1_data.
    //
    // Only the chosen design's own fields are validated and kept: asking a
    // Balloon Pop card to carry a countdown length, or a Countdown card to
    // carry a letter, would put values in the JSON that nothing ever reads and
    // that the client never saw a box for.
    public function saveProposalContent(Request $request)
    {
        $card = $this->currentDraft();
        $card->occasion = 'proposal';
        $design = (int) ($card->variant ?: 1);
        $spec = self::proposalDesign($design);

        $rules = [
            'to_name' => 'nullable|string|max:' . self::PROPOSAL_LIMITS['to_name'],
            'from_name' => 'nullable|string|max:' . self::PROPOSAL_LIMITS['from_name'],
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|max:5120',
        ];
        foreach (self::proposalTextKeys($design) as $key) {
            $rules[$key] = 'nullable|string|max:' . self::PROPOSAL_LIMITS[$key];
        }

        $validator = Validator::make($request->all(), $rules);

        // A chat thread has room for so many bubbles and a roll for so many
        // captions, so these fields are checked for shape as well as length:
        // one line in is one object on the page.
        $validator->after(function ($validator) use ($request, $spec) {
            foreach (self::PROPOSAL_MULTILINE as $key => $maxLines) {
                if (! in_array($key, $spec['fields'], true)) {
                    continue;
                }
                $lines = preg_split('/\r\n|\r|\n/', (string) $request->input($key));
                if (count(array_filter($lines, fn ($l) => trim($l) !== '')) > $maxLines) {
                    $validator->errors()->add($key,
                        'That is more than ' . $maxLines . ' lines.');
                }
            }
        });

        $data = $validator->validate();

        $existing = $card->gift1_data ?? [];
        $photos = $existing['photos'] ?? [];

        foreach ($request->file('photos', []) as $key => $file) {
            if (! $file || ! in_array($key, $spec['photos'], true)) {
                continue;
            }
            if (! empty($photos[$key])) {
                Storage::disk('public')->delete($photos[$key]);
            }
            $photos[$key] = $file->store('birthday-cards/proposal', 'public');
        }

        // A design change can leave a photo behind that the new design has no
        // slot for; it stays on disk but is not carried into the payload.
        $photos = array_intersect_key($photos, array_flip($spec['photos']));

        $content = [
            'design' => $design,
            'theme' => (int) ($card->gift_screen_variant ?: 1),
            'to_name' => $data['to_name'] ?? null,
            'from_name' => $data['from_name'] ?? null,
            'photos' => $photos,
        ];
        foreach (self::proposalTextKeys($design) as $key) {
            $content[$key] = $this->normaliseNewlines($data[$key] ?? null);
        }

        $card->gift1_data = $content;
        $card->current_step = max($card->current_step, 3);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    // Step 1 — save theme + variant selection
    public function saveStep1(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|in:boy,girl',
            'variant' => 'required|integer|in:1,2',
        ]);

        $card = $this->currentDraft();
        $card->theme = $data['theme'];
        $card->variant = $data['variant'];
        $card->current_step = max($card->current_step, 2);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    // Step 2 — save 4-digit PIN + photo (also re-confirms step 1 theme/variant)
    public function saveStep2(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|in:boy,girl',
            'variant' => 'required|integer|in:1,2',
            'lock_code' => 'required|digits:4',
            'photo' => 'nullable|image|max:5120',
        ]);

        $card = $this->currentDraft();
        $card->theme = $data['theme'];
        $card->variant = $data['variant'];
        $card->lock_code = $data['lock_code'];

        if ($request->hasFile('photo')) {
            if ($card->profile_image_path) {
                Storage::disk('public')->delete($card->profile_image_path);
            }
            $card->profile_image_path = $request->file('photo')->store('birthday-cards/profile', 'public');
        }

        $card->current_step = max($card->current_step, 3);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'profile_image_url' => $card->profile_image_path ? Storage::url($card->profile_image_path) : null,
        ]);
    }

    // Step 3 — save welcome screen heading + message
    public function saveStep3(Request $request)
    {
        $data = $request->validate([
            'heading' => 'nullable|string|max:' . self::WELCOME_LIMITS['heading'],
            'message' => 'nullable|string|max:' . self::WELCOME_LIMITS['message'],
        ]);

        $card = $this->currentDraft();
        $card->heading = $data['heading'] ?? null;
        $card->welcome_message = $this->normaliseNewlines($data['message'] ?? null);
        $card->current_step = max($card->current_step, 4);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    // Step 4 — save which gift-box screen design was chosen
    public function saveStep4(Request $request)
    {
        $data = $request->validate([
            'gift_screen_variant' => 'required|integer|in:1,2',
        ]);

        $card = $this->currentDraft();
        $card->gift_screen_variant = $data['gift_screen_variant'];
        $card->current_step = max($card->current_step, 5);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    /**
     * Step 5 — Gift 1: theme choice (1-4), up to 3 photos, and — for the girl
     * design — the special date its calendar marks and the note beside it.
     *
     * The girl fields are always accepted and stored, even on a boy card:
     * a client who switches theme after filling them in keeps their work, and
     * the boy templates simply have nowhere to show them.
     */
    public function saveStep5(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'nullable|image|max:5120',
            'cal_date' => 'nullable|date',
            'message' => 'nullable|string|max:' . self::GIFT1_GIRL_LIMITS['message'],
        ]);

        $card = $this->currentDraft();
        $existing = $card->gift1_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null];

        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift1', 'public');
        }

        $card->gift1_data = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            // Girl design only — the calendar's marked day and the note.
            // The full date is kept so the picker can be restored; the month
            // name and the month's length are derived at render time.
            'cal_date' => $data['cal_date'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
        ];
        $card->current_step = max($card->current_step, 6);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    // Step 6 — Gift 2: theme choice (1-4) + up to 4 photos + names + date + note
    public function saveStep6(Request $request)
    {
        $card = $this->currentDraft();
        $messageLimit = $card->theme === 'girl'
            ? self::GIFT2_LIMITS['message_girl']
            : self::GIFT2_LIMITS['message_boy'];

        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:4',
            'photos.*' => 'nullable|image|max:5120',
            'name_first' => 'nullable|string|max:' . self::GIFT2_LIMITS['name_first'],
            'name_second' => 'nullable|string|max:' . self::GIFT2_LIMITS['name_second'],
            'cal_date' => 'nullable|date',
            'message' => 'nullable|string|max:' . $messageLimit,
            'signed' => 'nullable|string|max:' . self::GIFT2_LIMITS['signed'],
        ] + array_map(
            fn($limit) => 'nullable|string|max:' . $limit,
            self::GIFT2_GIRL_LIMITS
        ));

        $existing = $card->gift2_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null, null];

        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift2', 'public');
        }

        $gift2 = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'cal_date' => $data['cal_date'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
        ];
        // Girl design only — the wrapped box and the three polaroid captions.
        foreach (array_keys(self::GIFT2_GIRL_LIMITS) as $key) {
            $gift2[$key] = $data[$key] ?? null;
        }

        $card->gift2_data = $gift2;
        $card->current_step = max($card->current_step, 7);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    /**
     * Design-safe text limits, in characters.
     *
     * These cards are fixed layouts, not documents: every slot has only the
     * room its own design gives it, so each ceiling is derived from the space
     * that slot actually has at the smallest size the page renders at
     * (measured in a browser — see DASHBOARD_WIZARD_DOCUMENTATION.md).
     * The dashboard reads the same numbers for its `maxlength` and counters,
     * so the field cannot accept what the design cannot show.
     */

    /** Step 3 — the welcome screen. */
    public const WELCOME_LIMITS = [
        'heading' => 40,   // display face, ~16 chars a line, wraps to 2-3
        'message' => 160,  // ~40 chars a line, room for 4
    ];

    /**
     * Step 5 — Gift 1.
     *
     * Only the girl design has these: it pairs the three photos with a mini
     * calendar and a handwritten note. The boy design is a photo board and
     * nothing else, which is why this step used to collect photos alone — and
     * why the girl side was being asked for the boy design's fields.
     *
     * `cal_month` and the month's length are derived from the picked date, not
     * typed, so only the note needs a ceiling.
     */
    public const GIFT1_GIRL_LIMITS = [
        'message' => 90,   // a small note panel sharing a row with the calendar
    ];

    /** Step 6 — Gift 2. The note lives in a different box per theme. */
    public const GIFT2_LIMITS = [
        'name_first' => 14,
        'name_second' => 14,
        'signed' => 30,
        // Boy: a fixed note panel under the calendar. Girl: a letter sheet
        // that is both taller and scrollable.
        'message_boy' => 180,
        'message_girl' => 300,
    ];

    /**
     * Step 6 — the girl Gift 2 scene, which the boy design has no equivalent
     * for: a wrapped box that opens onto three polaroids, then an envelope.
     * Each slot is a single line in a fixed spot, so each ceiling is the width
     * that spot has at the narrowest render.
     */
    public const GIFT2_GIRL_LIMITS = [
        'box_title' => 34,   // the line above the gift box
        'box_hint' => 20,    // the smaller prompt under it
        'cap1' => 18,        // handwritten strip under a polaroid
        'cap2' => 18,
        'cap3' => 18,
    ];

    /**
     * Step 7 — every text slot in the Gift 3 book, in book-page order, with
     * its limit. Whitelisted so the stored JSON keeps a known shape, and so
     * the dashboard, the save endpoint and the template all agree on one set
     * of keys.
     */
    public const GIFT3_TEXT_LIMITS = [
        // Book page 1 — title
        'eyebrow' => 28,
        'from_name' => 18,        // script face at up to 42px
        'to_name' => 18,
        // Book page 2 — big photo
        'caption' => 45,
        // Book page 3 — memory
        'memory_text' => 70,      // narrow half-width column beside the photo
        // Book page 4 — polaroids
        'polaroid_label' => 32,
        'note1' => 18,            // handwritten strip under a polaroid
        'note2' => 18,
        'note3' => 18,
        // Book page 5 — letter
        'letter_label' => 32,
        'letter' => 280,          // see GIFT3_LETTER_MAX_LINES as well
        'envelope_hint' => 28,
        // Book page 6 — special dates
        'dates_label' => 32,
        'date1_name' => 22,       // shares its row with the date itself
        'date2_name' => 22,
        'date3_name' => 22,
        'date4_name' => 22,
        // Book page 7 — future dreams
        'dreams_label' => 32,
        'dream1' => 24,
        'dream2' => 24,
        'dream3' => 24,
        'dream4' => 24,
        // Book page 8 — quote
        'quote' => 120,
        // Book page 9 — secret
        'secret_label' => 32,
        'secret_button' => 20,
        'secret_message' => 48,
        // Book page 10 — final
        'final_line1' => 42,
        'final_line2' => 42,
        'replay_label' => 20,
        'close_label' => 20,
    ];

    /**
     * The letter is the one multi-line slot, so it needs a line ceiling too —
     * 280 characters of "a\n" would still walk off the paper.
     */
    public const GIFT3_LETTER_MAX_LINES = 10;

    /**
     * Step 7 — the girl Gift 3, which is a different thing entirely: a phone
     * with a camera roll on it, scrolled through card by card. Photos, a chat
     * screen, two video clips, a pinned note and a letter, rather than a book.
     *
     * Every slot below is a line in a fixed card, so each ceiling is the room
     * that slot has at the narrowest render.
     */
    public const GIFT3_GIRL_TEXT_LIMITS = [
        // The cover and the gallery header
        'cover_title' => 22,
        'cover_sub' => 34,
        'cover_tap' => 18,
        'gallery_title' => 22,
        // Card 1 — a photo with its date
        'p1_date' => 14,
        'p1_place' => 18,
        'p1_caption' => 34,       // three lines of the italic caption row
        // Card 2 — the video clip
        'v1_date' => 14,
        'v1_place' => 18,
        'v1_caption' => 34,
        'v1_duration' => 6,       // the pill over the play button, "0:18"
        // Card 3 — the chat, either an uploaded screenshot or these three lines
        'chat_name' => 18,
        'chat_date' => 14,
        'chat_caption' => 34,
        'chat1' => 44,            // a bubble, at most two lines of it
        'chat2' => 44,
        'chat3' => 44,
        // Card 4 — the second photo
        'p2_date' => 14,
        'p2_place' => 18,
        'p2_caption' => 34,
        // Card 5 — the letter, opened full screen
        'letter' => 420,          // see GIFT3_GIRL_LETTER_MAX_LINES
        'signoff' => 22,
    ];

    /** The letter is the one girl slot characters alone don't bound. */
    public const GIFT3_GIRL_LETTER_MAX_LINES = 12;

    /**
     * The girl camera roll's four image slots, in the order the dashboard
     * shows them: the first photo, the video's cover still, the chat
     * screenshot, then the second photo.
     */
    public const GIFT3_GIRL_PHOTO_KEYS = ['photo1', 'poster1', 'chat_shot', 'photo2'];

    /** The single clip the video card plays. */
    public const GIFT3_GIRL_VIDEO_KEYS = ['video1'];

    /**
     * A clip is by far the largest thing a client uploads, so it goes up on
     * its own (see uploadGift3Video) rather than riding along with the rest of
     * the step. That keeps every request comfortably inside the server's
     * `post_max_size`, and this ceiling is what both ends enforce.
     *
     * It must stay at or below PHP's own `upload_max_filesize`, or the file is
     * discarded before Laravel ever sees it.
     */
    public const GIFT3_GIRL_VIDEO_MAX_KB = 2048;

    /** Key order for the girl Gift 3 text slots. */
    public static function gift3GirlTextKeys(): array
    {
        return array_keys(self::GIFT3_GIRL_TEXT_LIMITS);
    }

    /** Book page 6 — the date pickers, stored as ISO so they can be restored. */
    public const GIFT3_DATE_KEYS = [
        'date1_value',
        'date2_value',
        'date3_value',
        'date4_value',
    ];

    /** Checked/unchecked state of the "Future Dreams" checklist items. */
    public const GIFT3_FLAG_KEYS = [
        'dream1_done',
        'dream2_done',
        'dream3_done',
        'dream4_done',
    ];

    /** The checklist page holds three rows, or four if the client adds one. */
    public const GIFT3_MIN_DREAMS = 3;

    public const GIFT3_MAX_DREAMS = 4;

    /** Key order for the Gift 3 text slots. */
    public static function gift3TextKeys(): array
    {
        return array_keys(self::GIFT3_TEXT_LIMITS);
    }

    /**
     * Step 7 — Gift 3: the "Our Story" book.
     *
     * Theme choice (1-4), the 5 photos the book lays out, and the text of
     * all 10 of its pages. Only the closed cover stays fixed.
     */
    public function saveStep7(Request $request)
    {
        $card = $this->currentDraft();
        $isGirl = $card->theme === 'girl';

        $rules = [
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|max:5120',
        ];

        if ($isGirl) {
            // The clip is not part of this request — it is uploaded on its own
            // beforehand, so a large video can never push the step past the
            // server's post_max_size. See uploadGift3Video.
            $rules['photos'] = 'nullable|array|max:4';
            foreach (self::GIFT3_GIRL_TEXT_LIMITS as $key => $limit) {
                $rules[$key] = 'nullable|string|max:' . $limit;
            }
        } else {
            $rules['dream_count'] = 'required|integer|between:'
                . self::GIFT3_MIN_DREAMS . ',' . self::GIFT3_MAX_DREAMS;
            foreach (self::GIFT3_TEXT_LIMITS as $key => $limit) {
                $rules[$key] = 'nullable|string|max:' . $limit;
            }
            foreach (self::GIFT3_DATE_KEYS as $key) {
                $rules[$key] = 'nullable|date_format:Y-m-d';
            }
            foreach (self::GIFT3_FLAG_KEYS as $key) {
                $rules[$key] = 'nullable|boolean';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        // The multi-line slots are the ones where a character count alone
        // doesn't bound the height.
        $lineCaps = $isGirl
            ? ['letter' => self::GIFT3_GIRL_LETTER_MAX_LINES]
            : ['letter' => self::GIFT3_LETTER_MAX_LINES];

        $validator->after(function ($validator) use ($request, $lineCaps) {
            foreach ($lineCaps as $field => $max) {
                $value = (string) $request->input($field);
                if ($value === '') {
                    continue;
                }
                $lines = substr_count($this->normaliseNewlines($value), "\n") + 1;
                if ($lines > $max) {
                    $validator->errors()->add(
                        $field,
                        'This cannot be more than ' . $max . ' lines long.'
                    );
                }
            }
        });

        $data = $validator->validate();

        $existing = $card->gift3_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null, null, null];

        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift3', 'public');
        }

        $gift3 = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
        ];

        if ($isGirl) {
            // Whatever uploadGift3Video already stored stays as it is.
            $gift3['videos'] = $existing['videos'] ?? [null];

            foreach (self::GIFT3_GIRL_TEXT_LIMITS as $key => $limit) {
                $gift3[$key] = $data[$key] ?? null;
            }
            $gift3['letter'] = $this->normaliseNewlines($gift3['letter']);
        } else {
            $gift3['dream_count'] = (int) $data['dream_count'];
            foreach (self::GIFT3_TEXT_LIMITS as $key => $limit) {
                $gift3[$key] = $data[$key] ?? null;
            }
            $gift3['letter'] = $this->normaliseNewlines($gift3['letter']);
            foreach (self::GIFT3_DATE_KEYS as $key) {
                $gift3[$key] = $data[$key] ?? null;
            }
            foreach (self::GIFT3_FLAG_KEYS as $key) {
                $gift3[$key] = $request->boolean($key);
            }
        }

        // Anything the other side had stays put, so switching theme mid-build
        // doesn't throw away work.
        $card->gift3_data = array_merge($existing, $gift3);
        $card->current_step = max($card->current_step, 8);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn($p) => $p ? Storage::url($p) : null, $photos),
            'video_urls' => array_map(
                fn($v) => $v ? Storage::url($v) : null,
                $gift3['videos'] ?? [null]
            ),
        ]);
    }

    /**
     * Step 8 — the ending page ("One Last Thing").
     *
     * The design is an envelope that opens into a letter sheet. The sheet
     * scrolls, so the letter is the one slot with real slack; everything else
     * is a single line in a fixed spot and its ceiling is the width that spot
     * has at the narrowest render.
     */
    public const ENDING_TEXT_LIMITS = [
        'title' => 28,           // display face at up to 28px, one line
        'subtitle' => 48,        // 13px italic under the title
        'tap_label' => 20,       // 12px uppercase, 2px tracking, beside an icon
        'letter_heading' => 32,  // 11px uppercase, 3px tracking
        'letter' => 500,         // the paper scrolls; see ENDING_LETTER_MAX_LINES
        'signoff' => 28,         // 24px hand, right-aligned on one line
        'end_label' => 20,       // 11px uppercase closing stamp
    ];

    /**
     * The letter is written out a character at a time, so its length is a
     * time budget as much as a space one — 500 characters already takes the
     * best part of half a minute to appear.
     */
    public const ENDING_LETTER_MAX_LINES = 14;

    /**
     * Step 8 — the girl ending page, which is a different design, not a
     * recoloured boy one: a closed flower that blooms into a round keepsake
     * card. A circle holds noticeably less than a rectangular sheet, and the
     * card does not scroll, so its note is capped tighter than the boy's.
     */
    public const ENDING_GIRL_TEXT_LIMITS = [
        'title' => 24,           // display italic over the flower
        'subtitle' => 40,
        'tap_label' => 18,       // uppercase, 2.4px tracking, beside an icon
        'letter_heading' => 26,  // the small cap line inside the circle
        'letter' => 180,         // measured with every other slot at its own limit too
        'signoff' => 22,
        'end_label' => 18,
    ];

    public const ENDING_GIRL_LETTER_MAX_LINES = 8;

    /** The ending text slots for a theme, with the right ceilings. */
    public static function endingLimits(?string $theme): array
    {
        return $theme === 'girl' ? self::ENDING_GIRL_TEXT_LIMITS : self::ENDING_TEXT_LIMITS;
    }

    public static function endingLetterMaxLines(?string $theme): int
    {
        return $theme === 'girl' ? self::ENDING_GIRL_LETTER_MAX_LINES : self::ENDING_LETTER_MAX_LINES;
    }

    /**
     * Step 8 — the four ending-page designs per theme.
     *
     * Each one is an existing template reached at /{theme}/page/4/{n}, so the
     * registry only has to name them and say whether the side is wired up.
     * The girl designs exist as templates but are not integrated yet; flipping
     * `available` is what turns them on, and nothing else here changes.
     */
    public const ENDING_THEMES = [
        'boy' => [
            1 => ['name' => 'Cool Steel', 'blurb' => 'Ivory blue paper, midnight navy ink', 'available' => true],
            2 => ['name' => 'Graphite Ice', 'blurb' => 'Dark graphite card, ice blue ink', 'available' => true],
            3 => ['name' => 'Midnight Gold', 'blurb' => 'Deep navy card, warm gold ink', 'available' => true],
            4 => ['name' => 'Slate Emerald', 'blurb' => 'Slate charcoal card, emerald ink', 'available' => true],
        ],
        // The girl designs are their own thing — a flower that blooms into a
        // round keepsake card, not the boy's envelope in pink.
        'girl' => [
            1 => ['name' => 'Blush Rose', 'blurb' => 'Blush petals on cream', 'available' => true],
            2 => ['name' => 'Lilac Dusk', 'blurb' => 'Lavender petals on soft violet', 'available' => true],
            3 => ['name' => 'Rose Gold Noir', 'blurb' => 'Dark field, rose gold bloom', 'available' => true],
            4 => ['name' => 'Plum Midnight', 'blurb' => 'Midnight field, plum bloom', 'available' => true],
        ],
    ];

    /**
     * Step 9 — the four QR designs per theme.
     *
     * Everything here is cosmetic: App\Support\QrRenderer always keeps the
     * full quiet zone, high error correction and a light plate under the
     * symbol, so every theme scans. The girl set is defined but not wired up
     * yet, exactly like the ending designs above.
     *
     * @see \App\Support\QrRenderer
     */
    public const QR_THEMES = [
        'boy' => [
            1 => [
                'name' => 'Midnight Navy',
                'blurb' => 'Classic squares, ivory card',
                'available' => true,
                'bg' => '#EEF3F8',
                'plate' => '#FBFDFF',
                'module' => '#16233A',
                'eye_frame' => '#2C5A80',
                'eye_ball' => '#16233A',
                'shape' => 'square',
                'frame' => 'solid',
                'frame_color' => '#C6D8E8',
                'label' => 'Scan to open',
                'label_color' => '#2C5A80',
                'radius' => 0.06,
            ],
            2 => [
                'name' => 'Steel Glow',
                'blurb' => 'Rounded modules, blue gradient',
                'available' => true,
                'bg' => '#FFFFFF',
                'plate' => '#FFFFFF',
                'module' => '#2C5A80',
                'module_alt' => '#4E80B0',
                'eye_frame' => '#2C5A80',
                'eye_ball' => '#3E6E96',
                'shape' => 'rounded',
                'frame' => 'none',
                'label' => 'Open our story',
                'label_color' => '#2C5A80',
                'radius' => 0.09,
            ],
            3 => [
                'name' => 'Graphite Ice',
                'blurb' => 'Dark card, ice blue dots',
                'available' => true,
                'bg' => '#0D1117',
                'plate' => '#EAF3FA',
                'module' => '#16283A',
                'eye_frame' => '#274B67',
                'eye_ball' => '#16283A',
                'shape' => 'dot',
                'frame' => 'solid',
                'frame_color' => '#274B67',
                'label' => 'Scan me',
                'label_color' => '#BFD9EC',
                'radius' => 0.08,
            ],
            4 => [
                'name' => 'Blueprint',
                'blurb' => 'Dashed frame, plain squares',
                'available' => true,
                'bg' => '#E4EDF6',
                'plate' => '#FFFFFF',
                'module' => '#16233A',
                'eye_frame' => '#16233A',
                'eye_ball' => '#3E6E96',
                'shape' => 'square',
                'frame' => 'dashed',
                'frame_color' => '#6E9FC9',
                'label' => '',
                'radius' => 0.03,
            ],
        ],
        // The girl set is deliberately its own family: rounded and dot modules
        // throughout rather than the boy set's hard squares, warm blush and
        // lilac palettes instead of navy and graphite, and a double card border
        // the boy designs never use.
        'girl' => [
            1 => [
                'name' => 'Blush Petal',
                'blurb' => 'Rounded modules, petal corners, double blush border',
                'available' => true,
                'bg' => '#FFF3F6',
                'plate' => '#FFFBFC',
                'module' => '#7A3247',
                'module_alt' => '#B05B74',
                'eye_frame' => '#A34E67',
                'eye_ball' => '#7A3247',
                'shape' => 'rounded',
                'frame' => 'double',
                'frame_color' => '#E8899F',
                'label' => 'Scan to bloom',
                'label_color' => '#C96A85',
                'motif' => 'petal',
                'motif_color' => '#E8899F',
                'radius' => 0.10,
            ],
            2 => [
                'name' => 'Lilac Confetti',
                'blurb' => 'Soft dots on lilac, sparkle corners',
                'available' => true,
                'bg' => '#F5F1FD',
                'plate' => '#FFFFFF',
                'module' => '#3F2A63',
                'module_alt' => '#6D51A0',
                'eye_frame' => '#7A5BAE',
                'eye_ball' => '#3F2A63',
                'shape' => 'dot',
                'frame' => 'none',
                'label' => 'Open our story',
                'label_color' => '#7A5BAE',
                'motif' => 'sparkle',
                'motif_color' => '#A98BD8',
                'radius' => 0.13,
            ],
            3 => [
                'name' => 'Rose Gold Noir',
                'blurb' => 'Dark card, rose gold dots, heart corners',
                'available' => true,
                'bg' => '#1C1116',
                'plate' => '#FBF1F4',
                'module' => '#3A2028',
                'module_alt' => '#6B3B49',
                'eye_frame' => '#8C5566',
                'eye_ball' => '#3A2028',
                'shape' => 'dot',
                'frame' => 'double',
                'frame_color' => '#C58490',
                'label' => 'Scan me',
                'label_color' => '#E7C3C9',
                'motif' => 'heart',
                'motif_color' => '#C58490',
                'radius' => 0.11,
            ],
            4 => [
                'name' => 'Plum Midnight',
                'blurb' => 'Rounded modules on deep plum, heart corners',
                'available' => true,
                'bg' => '#150F22',
                'plate' => '#F4EFFC',
                'module' => '#2E2440',
                'module_alt' => '#54407C',
                'eye_frame' => '#6B52A0',
                'eye_ball' => '#2E2440',
                'shape' => 'rounded',
                'frame' => 'solid',
                'frame_color' => '#7C5AAE',
                'label' => 'A little something',
                'label_color' => '#C7A6F0',
                'motif' => 'heart',
                'motif_color' => '#9E7BD0',
                'radius' => 0.09,
            ],
        ],
        // Anniversary is its own family of six rather than the boy/girl four:
        // the palettes follow the four anniversary card variants (taupe,
        // maroon, peach gold, bright red) and two darker cards round the set
        // out, so a couple picks a code that matches the story it opens.
        'anniversary' => [
            1 => [
                'name' => 'Taupe Vow',
                'blurb' => 'Charcoal squares on a warm ivory card',
                'available' => true,
                'bg' => '#EFEAE0',
                'plate' => '#FBF8F2',
                'module' => '#141312',
                'eye_frame' => '#5A5147',
                'eye_ball' => '#141312',
                'shape' => 'square',
                'frame' => 'solid',
                'frame_color' => '#C7BCA6',
                'label' => 'Scan to open',
                'label_color' => '#5A5147',
                'radius' => 0.06,
            ],
            2 => [
                'name' => 'Maroon & Gold',
                'blurb' => 'Rounded modules, gold double border, hearts',
                'available' => true,
                'bg' => '#F6ECD6',
                'plate' => '#FFFCF4',
                'module' => '#5C1420',
                'module_alt' => '#8F2230',
                'eye_frame' => '#A3792F',
                'eye_ball' => '#5C1420',
                'shape' => 'rounded',
                'frame' => 'double',
                'frame_color' => '#A3792F',
                'label' => 'Our story',
                'label_color' => '#8F2230',
                'motif' => 'heart',
                'motif_color' => '#A3792F',
                'radius' => 0.10,
            ],
            3 => [
                'name' => 'Peach Gold',
                'blurb' => 'Soft dots on ivory, petal corners',
                'available' => true,
                'bg' => '#FAF3E8',
                'plate' => '#FFFFFF',
                'module' => '#7F5A30',
                'module_alt' => '#B8853F',
                'eye_frame' => '#E0A865',
                'eye_ball' => '#7F5A30',
                'shape' => 'dot',
                'frame' => 'solid',
                'frame_color' => '#E0A865',
                'label' => 'Scan for us',
                'label_color' => '#9C7C52',
                'motif' => 'petal',
                'motif_color' => '#E0A865',
                'radius' => 0.12,
            ],
            4 => [
                'name' => 'Crimson & White',
                'blurb' => 'Bright red rounded modules, heart corners',
                'available' => true,
                'bg' => '#FDF2EF',
                'plate' => '#FFFFFF',
                'module' => '#A3140B',
                'module_alt' => '#E8281A',
                'eye_frame' => '#E8281A',
                'eye_ball' => '#A3140B',
                'shape' => 'rounded',
                'frame' => 'double',
                'frame_color' => '#E8281A',
                'label' => 'Open our story',
                'label_color' => '#C4291C',
                'motif' => 'heart',
                'motif_color' => '#E8281A',
                'radius' => 0.09,
            ],
            5 => [
                'name' => 'Ivory Minimal',
                'blurb' => 'Plain squares, no border — quiet and clean',
                'available' => true,
                'bg' => '#FFFFFF',
                'plate' => '#FFFFFF',
                'module' => '#2F2B26',
                'eye_frame' => '#2F2B26',
                'eye_ball' => '#8F7F65',
                'shape' => 'square',
                'frame' => 'none',
                'label' => '',
                'radius' => 0.03,
            ],
            6 => [
                'name' => 'Midnight Vow',
                'blurb' => 'Dark card, rose gold dots, sparkle corners',
                'available' => true,
                'bg' => '#17110F',
                'plate' => '#FBF4EE',
                'module' => '#2C1D18',
                'module_alt' => '#6B4438',
                'eye_frame' => '#A3792F',
                'eye_ball' => '#2C1D18',
                'shape' => 'dot',
                'frame' => 'double',
                'frame_color' => '#A3792F',
                'label' => 'Scan me',
                'label_color' => '#E4C79A',
                'motif' => 'sparkle',
                'motif_color' => '#C99B57',
                'radius' => 0.11,
            ],
        ],
        // The proposal family is six of its own, drawn from the four designs'
        // palettes rather than from the birthday or anniversary sets — a
        // proposal card carries no boy/girl `theme` at all, so the occasion is
        // what picks the family (see qrThemesForCard).
        'proposal' => [
            1 => [
                'name' => 'Rose Gold Vow',
                'blurb' => 'Rounded modules on warm cream, petal corners',
                'available' => true,
                'bg' => '#F7ECE2',
                'plate' => '#FFFAF4',
                'module' => '#7A3B36',
                'module_alt' => '#A35A56',
                'eye_frame' => '#A35A56',
                'eye_ball' => '#7A3B36',
                'shape' => 'rounded',
                'frame' => 'solid',
                'frame_color' => '#C98F6D',
                'label' => 'Scan to open',
                'label_color' => '#8A6A5C',
                'motif' => 'petal',
                'motif_color' => '#C98F6D',
                'radius' => 0.10,
            ],
            2 => [
                'name' => 'Midnight Velvet',
                'blurb' => 'Dark card, gold dots, sparkle corners',
                'available' => true,
                'bg' => '#1B2036',
                'plate' => '#FBF6EA',
                'module' => '#2F3A63',
                'eye_frame' => '#8A6C2E',
                'eye_ball' => '#2F3A63',
                'shape' => 'dot',
                'frame' => 'solid',
                'frame_color' => '#D9B26A',
                'label' => 'Open it',
                'label_color' => '#E7CE97',
                'motif' => 'sparkle',
                'motif_color' => '#D9B26A',
                'radius' => 0.09,
            ],
            3 => [
                'name' => 'Burgundy Seal',
                'blurb' => 'Deep red squares, gold double border, hearts',
                'available' => true,
                'bg' => '#F6ECD6',
                'plate' => '#FFFDF6',
                'module' => '#5C1420',
                'eye_frame' => '#8A2231',
                'eye_ball' => '#5C1420',
                'shape' => 'square',
                'frame' => 'double',
                'frame_color' => '#C9A75C',
                'label' => 'A question inside',
                'label_color' => '#8A2231',
                'motif' => 'heart',
                'motif_color' => '#A35A56',
                'radius' => 0.06,
            ],
            4 => [
                'name' => 'Blush Petal',
                'blurb' => 'Soft dots on blush, petal corners, no border',
                'available' => true,
                'bg' => '#FDF2F5',
                'plate' => '#FFFFFF',
                'module' => '#8A3D55',
                'module_alt' => '#C2607F',
                'eye_frame' => '#C2607F',
                'eye_ball' => '#8A3D55',
                'shape' => 'dot',
                'frame' => 'none',
                'label' => 'Scan me',
                'label_color' => '#C2607F',
                'motif' => 'petal',
                'motif_color' => '#E9B7C7',
                'radius' => 0.10,
            ],
            5 => [
                'name' => 'Emerald Band',
                'blurb' => 'Rounded emerald modules on ivory, gold rule',
                'available' => true,
                'bg' => '#EDF3EF',
                'plate' => '#FBF8EC',
                'module' => '#123A33',
                'module_alt' => '#1E5C4D',
                'eye_frame' => '#1E5C4D',
                'eye_ball' => '#123A33',
                'shape' => 'rounded',
                'frame' => 'solid',
                'frame_color' => '#C69F57',
                'label' => 'Scan to open',
                'label_color' => '#1E5C4D',
                'radius' => 0.08,
            ],
            6 => [
                'name' => 'Bold Pop',
                'blurb' => 'Bright squares, dashed frame, nothing else',
                'available' => true,
                'bg' => '#DBE7FF',
                'plate' => '#FFFFFF',
                'module' => '#22305C',
                'eye_frame' => '#2F5FE0',
                'eye_ball' => '#22305C',
                'shape' => 'square',
                'frame' => 'dashed',
                'frame_color' => '#2F5FE0',
                'label' => '',
                'radius' => 0.04,
            ],
        ],
    ];

    /** Path the published story will live under — see the share URL below. */
    public const PUBLIC_CARD_PATH = 'c';

    /**
     * The girl Gift 1 calendar, from one picked date: the month it prints over
     * the grid, the day it marks, and how many days that month has — so
     * February doesn't render 31 cells. The dashboard's live preview derives
     * the same three values in JavaScript.
     */
    public static function girlCalendarParams(?string $date): array
    {
        $timestamp = $date ? strtotime($date) : false;
        if (! $timestamp) {
            return [];
        }

        return [
            'cal_month' => date('F', $timestamp),
            'cal_day' => (string) date('j', $timestamp),
            'cal_days' => (string) date('t', $timestamp),
        ];
    }

    /** Key order for the ending-page text slots. */
    public static function endingTextKeys(): array
    {
        return array_keys(self::ENDING_TEXT_LIMITS);
    }

    /** The ending designs for a theme, boy by default. */
    public static function endingThemes(?string $theme): array
    {
        return self::ENDING_THEMES[$theme] ?? self::ENDING_THEMES['boy'];
    }

    /** The QR designs for a theme, boy by default. */
    public static function qrThemes(?string $theme): array
    {
        return self::QR_THEMES[$theme] ?? self::QR_THEMES['boy'];
    }

    /**
     * The QR designs a given card may choose from.
     *
     * An anniversary or proposal card has no boy/girl `theme` at all — it
     * carries a `variant` instead — so keying off `theme` alone would silently
     * hand it the boy set. The occasion decides the family first (the key in
     * QR_THEMES is the occasion name), and only a birthday card, whose
     * occasion is not a family in there, falls through to its side's designs.
     */
    public static function qrThemesForCard(BirthdayCard $card): array
    {
        return self::QR_THEMES[$card->occasion]
            ?? self::qrThemes($card->theme);
    }

    /** Whether a side's designs are wired up yet (girl is not, for now). */
    public static function themeSideIsAvailable(array $themes): bool
    {
        foreach ($themes as $design) {
            if ($design['available'] ?? false) {
                return true;
            }
        }

        return false;
    }

    /**
     * The public address of a card. The story flow itself is not built yet,
     * but the address it will live at is fixed now, so a QR generated today
     * still points at the right place once that flow lands.
     */
    public static function shareUrl(?string $slug): ?string
    {
        return $slug ? url('/' . self::PUBLIC_CARD_PATH . '/' . $slug) : null;
    }

    /**
     * Every card gets its share slug as soon as it exists, not at publish
     * time: the QR encodes the URL, so the dashboard can only show a true preview
     * of the four designs if the address is already settled.
     */
    public static function ensureSlug(BirthdayCard $card): string
    {
        if ($card->slug) {
            return $card->slug;
        }

        $stem = Str::slug((string) (
            $card->gift3_data['to_name']
            ?? $card->gift1_data['to_name']
            ?? $card->gift1_data['name_first']
            ?? $card->recipient_name
            ?? ''
        )) ?: (in_array($card->occasion, ['anniversary', 'proposal'], true)
            ? $card->occasion
            : 'birthday');

        do {
            $slug = $stem . '-' . Str::lower(Str::random(6));
        } while (BirthdayCard::where('slug', $slug)->exists());

        $card->slug = $slug;
        $card->save();

        return $slug;
    }

    /** The four QR designs of a theme, rendered against a card's own URL. */
    public static function qrPreviews(?string $theme, ?string $slug, int $size = 300): array
    {
        $url = self::shareUrl($slug) ?? url('/' . self::PUBLIC_CARD_PATH . '/preview');

        $previews = [];
        foreach (self::qrThemes($theme) as $n => $design) {
            $previews[$n] = QrRenderer::dataUri($url, $design, $size);
        }

        return $previews;
    }

    /**
     * Step 7, girl side — the video clip, uploaded on its own.
     *
     * A clip dwarfs everything else the wizard sends. Bundled into the step's
     * multipart body alongside four photos and thirty text fields it pushed the
     * request past PHP's `post_max_size`, and the server answered 413 before
     * Laravel could say anything useful about it. One file per request keeps
     * every upload inside the budget, and the ceiling enforced here is the same
     * one the dashboard checks before it even starts sending.
     */
    public function uploadGift3Video(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime|max:'
                . self::GIFT3_GIRL_VIDEO_MAX_KB,
        ], [
            'video.max' => 'That clip is too large. The limit is '
                . round(self::GIFT3_GIRL_VIDEO_MAX_KB / 1024) . ' MB.',
            'video.mimetypes' => 'That file is not a video the card can play (MP4, WebM, OGG or MOV).',
        ]);

        $card = $this->currentDraft();
        $existing = $card->gift3_data ?? [];
        $videos = $existing['videos'] ?? [null];

        if (! empty($videos[0])) {
            Storage::disk('public')->delete($videos[0]);
        }
        $videos[0] = $request->file('video')->store('birthday-cards/gift3-video', 'public');

        $existing['videos'] = $videos;
        $card->gift3_data = $existing;
        $card->save();

        return response()->json([
            'success' => true,
            'video_url' => Storage::url($videos[0]),
        ]);
    }

    /**
     * Step 8 — the ending page: which of the four designs, and its text.
     */
    public function saveStep8(Request $request)
    {
        $card = $this->currentDraft();
        $limits = self::endingLimits($card->theme);
        $maxLines = self::endingLetterMaxLines($card->theme);

        // FormData submits textarea paragraph breaks as CRLF. Count the
        // normalised value instead, so those transport-only \r characters do
        // not make a note that fits the dashboard's maxlength fail validation.
        $input = $request->all();
        if (array_key_exists('letter', $input) && $input['letter'] !== null) {
            $input['letter'] = $this->normaliseNewlines((string) $input['letter']);
        }

        $rules = ['theme' => 'required|integer|in:1,2,3,4'];
        foreach ($limits as $key => $limit) {
            $rules[$key] = 'nullable|string|max:' . $limit;
        }

        $validator = Validator::make($input, $rules);

        // The letter is the one multi-line slot, so characters alone don't
        // bound it — the same treatment Gift 3's letter gets.
        $validator->after(function ($validator) use ($input, $maxLines) {
            $lines = substr_count((string) ($input['letter'] ?? ''), "\n") + 1;
            if ($lines > $maxLines) {
                $validator->errors()->add(
                    'letter',
                    'The letter cannot be more than ' . $maxLines . ' lines long.'
                );
            }
        });

        $data = $validator->validate();

        $ending = ['theme' => (int) $data['theme']];
        foreach ($limits as $key => $limit) {
            $ending[$key] = $data[$key] ?? null;
        }
        $ending['letter'] = $this->normaliseNewlines($ending['letter']);

        $card->ending_data = $ending;
        $card->current_step = max($card->current_step, 9);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    /**
     * How much of a song the story plays: two minutes, the way a social story
     * takes a slice of a track rather than the whole thing.
     *
     * It is a fixed length — both the minimum and the maximum. The client
     * chooses *which* two minutes, not how long. A song shorter than this has
     * no slice to pick out of it and plays whole.
     */
    public const MUSIC_CLIP_SECONDS = 120.0;

    /**
     * Save as Draft — name the card and park it.
     *
     * Every step already persists as it goes, so this does not save the card
     * content; what it adds is the label the client identifies the draft by on
     * the dashboard, and a record of how far they had got so Edit can reopen
     * at the right step.
     */
    public function saveDraft(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:80',
            'current_step' => 'nullable|integer|min:1|max:10',
        ]);

        $card = $this->currentDraft();

        if (! empty($data['title'])) {
            $card->title = $data['title'];
        }

        // Keep the furthest point reached — stepping back to review an earlier
        // step should not lose the progress already made.
        if (! empty($data['current_step'])) {
            $card->current_step = max((int) $card->current_step, (int) $data['current_step']);
        }

        $card->last_opened_at = now();
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'title' => $card->displayTitle(),
            'redirect' => route('client.cards'),
        ]);
    }

    /** Step 9 — save the client's music choice, and the part of it to play. */
    public function saveStep9(Request $request)
    {
        $data = $request->validate([
            'source' => 'required|in:library',
            'track_id' => 'required|integer|exists:music_tracks,id',
            'trim_start' => 'nullable|numeric|min:0',
            'trim_end' => 'nullable|numeric|min:0',
        ]);

        $card = $this->currentDraft();
        $music = ['source' => $data['source']];

        if ($data['source'] === 'library') {
            $track = MusicTrack::whereKey($data['track_id'] ?? 0)
                ->where('is_active', true)
                ->firstOrFail();
            $music += [
                'track_id' => $track->id,
                'title' => $track->title,
                'path' => $track->file_path,
            ];
        }

        $music += self::musicTrim($data['trim_start'] ?? null, $data['trim_end'] ?? null);

        $card->music_data = $music;
        $card->current_step = max($card->current_step, 9);
        $card->save();

        return response()->json(['success' => true, 'card_id' => $card->id]);
    }

    /**
     * The minute of the song the story should play, as the picker left it.
     *
     * The dashboard sends the window's two offsets in seconds, and the length
     * between them is settled there — but the length is the one thing a client
     * doesn't get to choose, so it is capped again here rather than trusted.
     * Anything that isn't a usable window — no end, or an end at or before its
     * start — is stored as no clip at all, and the song plays whole. The
     * public story reads these two keys, so a card saved before the picker
     * existed simply has no window and behaves exactly as it did.
     *
     * @return array{trim_start: float|null, trim_end: float|null}
     */
    private static function musicTrim(mixed $start, mixed $end): array
    {
        $none = ['trim_start' => null, 'trim_end' => null];

        if ($end === null || $end === '') {
            return $none;
        }

        $from = round(max(0.0, (float) $start), 2);
        $to = round(min((float) $end, $from + self::MUSIC_CLIP_SECONDS), 2);

        return $to <= $from ? $none : ['trim_start' => $from, 'trim_end' => $to];
    }

    /**
     * Step 10 — QR Select: save the chosen QR design and hand back the share
     * link plus the rendered code.
     *
     * The card's slug is settled here (or earlier, when the dashboard first
     * rendered the previews), so the link a client copies today is the link
     * the story will answer on once the public flow is built.
     */
    public function saveStep10(Request $request)
    {
        // The QR code is the gate. A client may build and edit a card freely,
        // but turning it into a shareable link needs an approved plan.
        if (! Auth::user()->hasActiveSubscription()) {
            return response()->json([
                'success' => false,
                'reason' => 'subscription_required',
                'message' => 'An active subscription is required to generate the QR code.',
            ], 403);
        }

        $data = $request->validate([
            'theme' => 'required|integer|min:1',
        ]);

        $card = $this->currentDraft();
        $designs = self::qrThemesForCard($card);

        // Birthday cards offer four designs, anniversary cards six, so which
        // numbers are valid is a property of the card rather than a fixed list.
        if (! isset($designs[(int) $data['theme']])) {
            throw ValidationException::withMessages([
                'theme' => 'That QR design is not one of the designs for this card.',
            ]);
        }

        $slug = self::ensureSlug($card);
        $design = $designs[(int) $data['theme']];

        $card->qr_data = [
            'theme' => (int) $data['theme'],
            'generated_at' => now()->toIso8601String(),
        ];
        $card->link_expires_at = now()->addDays(15);
        $card->link_disabled_at = null;
        if (Schema::hasColumn('birthday_cards', 'is_revision')) {
            $card->is_revision = false;
        }
        $card->current_step = max($card->current_step, 10);
        // Generating the code is what finishes a card — it moves out of
        // Drafts and into the completed list from here.
        $card->is_published = true;
        $card->save();

        $url = self::shareUrl($slug);

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'slug' => $slug,
            'share_url' => $url,
            'qr_svg' => QrRenderer::svg($url, $design, 720),
        ]);
    }
}
