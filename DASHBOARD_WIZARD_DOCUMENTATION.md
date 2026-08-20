# Dashboard Wizard & Public Story (Dynamic Card Builder)

Work log for the client dashboard card-builder wizard — making Steps 3 through 9 fully
dynamic with real live previews, and the underlying card templates data-driven — and for the
public story those settings drive.

**Scope of this document:** the whole loop. The wizard up to and including the **Ending Page
(Step 8)** and **QR Select (Step 9)** in sections 1-16, the **public story** a recipient opens
from the generated link in sections 17-19, the **girl side** of Pages 1-3, Gift 1 and
Gift 2 in sections 20-22, the **rest of the girl side** — Gift 3, its own ending design,
its own QR set and its public story — plus one boy bug fix in sections 23-27, and a
Step 8 error-messaging fix plus a dead-code cleanup in section 28, and the public gift-card
click-target correction in section 29. Both themes are now complete, dashboard through to the
public story.

**Corresponding commits:**

| Commit | Covers |
|---|---|
| `d9fa1c1` | Steps 3-6 (Welcome, Gift Box, Gift 1, Gift 2) — sections 1-6 below |
| `c4b818a` | Gift 3 as Step 7 — section 7 |
| `e44d212` | Gift 3 interactions + design-safe text limits — sections 8-10 |
| *(earlier)* | Ending Page as Step 8, QR Select as Step 9 — sections 11-14 |
| *(earlier)* | The public story at `/c/{slug}` — sections 17-19 |
| *(earlier)* | The girl side: Pages 1-3, Gift 1 and Gift 2 — sections 20-22 |
| *(earlier)* | Girl Gift 3, ending page, QR and public story; boy welcome fix — sections 23-27 |
| *(this change)* | Step 8 error surfacing fix + dead-code cleanup — section 28 |
| *(this change)* | Public gift-selection click-target correction — section 29 |

---

## 1. Database

Four migrations were added.

### `2026_08_13_000000_add_heading_and_gift_variant_to_birthday_cards_table.php`

| Column | Type | Purpose |
|---|---|---|
| `heading` | `string` nullable | Step 3 — welcome screen heading |
| `gift_screen_variant` | `unsignedTinyInteger` nullable | Step 4 — which gift-box screen design (1 or 2) |

### `2026_08_13_010000_add_gift1_gift2_data_to_birthday_cards_table.php`

| Column | Type | Purpose |
|---|---|---|
| `gift1_data` | `json` nullable | Step 5 — Gift 1 theme + photos |
| `gift2_data` | `json` nullable | Step 6 — Gift 2 theme + photos + names + date + text |

A third migration, for Gift 3's `gift3_data`, is covered in section 7.1; a fourth, for
the ending page and the QR code, in section 11.1.

### JSON shapes

```jsonc
// gift1_data
{ "theme": 1-4, "photos": ["path1", "path2", "path3"] }

// gift2_data
{
  "theme": 1-4,
  "photos": ["p1", "p2", "p3", "p4"],   // 4th only used by boy designs
  "name_first": "Emma",                  // boy designs only
  "name_second": "Lucas",                // boy designs only
  "cal_date": "2026-03-17",              // boy designs only — full date is stored
  "message": "…",
  "signed": "— always, E"                // boy designs only
}
```

> **Note on `cal_date`:** the full date is persisted (not just the day number) so the date
> picker can be correctly restored when the client reopens the dashboard. The day-of-month
> is derived at render time for the calendar heart marker.

### Model

`app/Models/BirthdayCard.php` — added `heading`, `gift_screen_variant`, `gift1_data`,
`gift2_data` to the `#[Fillable]` attribute list, and cast `gift1_data` / `gift2_data`
to `'array'`. `gift3_data`, `ending_data` and `qr_data` were added the same way as each
step landed.

---

## 2. Backend

### Controller — `app/Http/Controllers/Client/BirthdayCardController.php`

| Method | Saves | Advances `current_step` to |
|---|---|---|
| `saveStep1` | theme, variant | 2 |
| `saveStep2` | lock_code (PIN), profile photo | 3 |
| `saveStep3` | heading, welcome_message | 4 |
| `saveStep4` | gift_screen_variant | 5 |
| `saveStep5` | gift1_data (theme + up to 3 photos) | 6 |
| `saveStep6` | gift2_data (theme + up to 4 photos + names + date + text) | 7 |
| `saveStep7` | gift3_data (theme + 5 photos + every book page's text) | 8 |
| `saveStep8` | ending_data (design + the seven ending-page text slots) | 9 |
| `saveStep9` | qr_data (QR design) + the share slug; returns the link and the code | 9 |

All methods operate on `currentDraft()` — the client's latest unpublished `BirthdayCard`,
created on demand. Photo uploads merge into the existing photo array rather than replacing
it, so re-saving a step without re-picking every image does not wipe previously uploaded
photos. Replaced files are deleted from disk.

Uploads are stored on the `public` disk:
- `birthday-cards/profile/` — Step 2 profile photo
- `birthday-cards/gift1/` — Gift 1 photos
- `birthday-cards/gift2/` — Gift 2 photos
- `birthday-cards/gift3/` — Gift 3 photos

### Routes — `routes/web.php`

Added inside the authenticated `client.` prefix group:

```php
Route::post('/card/step3', [BirthdayCardController::class, 'saveStep3'])->name('card.step3');
Route::post('/card/step4', [BirthdayCardController::class, 'saveStep4'])->name('card.step4');
Route::post('/card/step5', [BirthdayCardController::class, 'saveStep5'])->name('card.step5');
Route::post('/card/step6', [BirthdayCardController::class, 'saveStep6'])->name('card.step6');
Route::post('/card/step7', [BirthdayCardController::class, 'saveStep7'])->name('card.step7');
Route::post('/card/step8', [BirthdayCardController::class, 'saveStep8'])->name('card.step8');
Route::post('/card/step9', [BirthdayCardController::class, 'saveStep9'])->name('card.step9');
```

---

## 3. Dashboard Wizard

`resources/views/client/dashboard.blade.php` — now **9 steps** (`const totalSteps = 9`).
Steps 1-6 landed first; Gift 3 was slotted in as Step 7; the old *Generate & Share* stub
was then split into the Ending Page and QR Select:

1. Choose Theme
2. Set Lock Code
3. Welcome Screen
4. Gift Box Screen
5. Gift 1
6. Gift 2
7. Gift 3 — see section 7
8. Ending Page — see section 11
9. QR Select — see section 12

### Step 3 — Welcome Screen

Replaced the old mock preview (fake avatar/name/message card) with the real thing:

- Two fields only: **Heading** and **Message**.
- Defaults auto-populate from the theme + design chosen in Step 1, matching each design's
  own original wording:

  | Theme | Variant | Default heading |
  |---|---|---|
  | boy | 1 | Happy Birthday My Love |
  | boy | 2 | Happy Birthday King |
  | girl | 1 | Happy Birthday My Love |
  | girl | 2 | Happy Birthday Princess |

- **Live preview** is an iframe of the actual public page (`/{theme}/page/2/{variant}`),
  not a mockup. It updates in real time as the user types (debounced ~250ms).
- If the user leaves the defaults alone, the defaults are saved. If they edit, their text
  is saved.

### Step 4 — Gift Box Screen

- Removed the old static "Gift 1 / Gift 2 / Gift 3" tab block, which contained
  non-functional placeholder fields (gift titles, gallery layout dropdowns, envelope
  colours, a fake book preview). None of it was wired to anything.
- Now shows **two live thumbnails** — the real `/{theme}/page/3/{1|2}` pages — and the user
  picks one.
- Selection is saved to `gift_screen_variant`.

### Step 5 — Gift 1

- **4 theme thumbnails**, each an iframe of the real page
  `/{theme}/page/3/{giftScreenVariant}/gift/1/{1-4}`.
- **3 photo upload slots** with inline thumbnail previews.
- **Live preview** iframe reflecting the chosen theme + uploaded photos in real time.

### Step 6 — Gift 2

- **4 theme thumbnails**, same pattern (`…/gift/2/{1-4}`).
- **Photo slots**, plus **Name 1 / Name 2**, **Special Date** (date picker), **Message**,
  and **Signed**.
- **Theme-aware field visibility** — the boy and girl Gift 2 designs are structurally
  different. The girl designs are a handwritten-letter layout with no names, calendar, or
  signature line, so those fields (and the 4th photo slot) are hidden when the girl theme
  is active, instead of collecting data the design cannot display.

  | Field | Boy | Girl |
  |---|---|---|
  | Photo slots | 4 | 3 |
  | Name 1 / Name 2 | shown | hidden |
  | Special Date | shown | hidden |
  | Message | shown | shown |
  | Signed | shown | hidden |

### Resume behaviour

All steps repopulate from the database on page load — theme, variant, PIN, heading,
message, gift-screen choice, gift themes, uploaded photo thumbnails, names, date, message
and signature. A client can close the dashboard mid-build and continue where they left off.

---

## 4. Card Templates Made Dynamic

36 template files under `resources/views/birthday/` were converted from hardcoded content
to query-parameter driven content, using Laravel's `request('key', 'default')` helper.

**Design principle:** every parameter has the template's *own original text* as its
fallback default. With no query string, each page renders byte-for-byte as it did before —
so the templates still work standalone, and the dashboard preview simply passes params.

### Welcome screen — page 2 (4 files)

`boy-page-2`, `boy-page-2-2`, `girl-page-2`, `girl-page-2-2`

| Param | Purpose |
|---|---|
| `heading` | Main title text |
| `message` | Body message (supports multi-line) |

### Gift 1 — boy (8 files)

`boy-page-3-variant-{1,2}-gift-1-page-{1..4}` — polaroid photo board.

| Param | Purpose |
|---|---|
| `photo1`, `photo2`, `photo3` | Replace the decorative SVG placeholders |

### Gift 1 — girl (8 files)

`girl-page-3-variant-{1,2}-gift-1-page-{1..4}` — memory board with calendar.

| Param | Purpose |
|---|---|
| `photo1`, `photo2`, `photo3` | Polaroid photos |
| `cal_month` | Calendar month label |
| `cal_day` | Highlighted day |
| `message` | Handwritten note |

### Gift 2 — boy (8 files)

`boy-page-3-variant-{1,2}-gift-2-page-{1..4}` — couple memory tiles + calendar + note.

| Param | Purpose |
|---|---|
| `photo1`–`photo4` | The four image tiles (walk / beach / coffee / embrace) |
| `name_first`, `name_second` | Couple names |
| `cal_month` | Calendar title |
| `cal_day` | Day marked with the heart |
| `message` | Note text |
| `signed` | Signature line |

### Gift 2 — girl (8 files)

`girl-page-3-variant-{1,2}-gift-2-page-{1..4}` — handwritten letter reveal.

| Param | Purpose |
|---|---|
| `photo1`, `photo2`, `photo3` | The three polaroids |
| `message` | Letter text — newlines split into handwritten lines |

---

## 5. Bug Fixes

**`setGlobalTheme()` crash (would break the whole dashboard).**
The function referenced `.preview-header`, a DOM element removed when Step 3 was rebuilt.
Switching theme threw a `TypeError`, which aborted the rest of the JS on that call. Fixed
by removing the stale references.

**Step 4 thumbnails showed the mobile fallback instead of the real design.**
The gift-box page swaps to a pure-CSS mobile layout below 1024px. Preview iframes were
rendered at a fixed 900px, landing under that breakpoint, so the thumbnails showed the CSS
fallback rather than the actual gift-box artwork. Fixed by giving those thumbs a wider
render width (`.gift-variant-thumb iframe` at 1280px) and making the scaling helper measure
each iframe's own width rather than assuming a single global value.

**Blade compile error with inline ternaries inside `@json()`.**
`@json(cond ? a : [...])` fails to compile ("Unclosed '['"). Resolved by assigning to a
variable in a `@php` block first, then `@json($var)`. This pattern is used in the girl
Gift 2 templates and in the dashboard's photo-URL bootstrapping.

---

## 6. Testing Performed

Verified with real browser automation (headless Chromium via Playwright) plus `curl`
checks, not just by inspection:

- **Blade compile + PHP lint** on every modified template.
- **`curl` per template group** — with no params (confirming defaults render unchanged) and
  with overrides (confirming every parameter takes effect).
- **Full boy-theme wizard run** — login → Step 1 through Step 7, including photo uploads,
  name/date/message entry, and asserting the live preview iframe reflected the typed values
  (verified the rendered `<em>` heading, names, and calendar marked-day inside the iframe).
- **Full girl-theme wizard run** — confirming theme-aware field visibility (names, calendar,
  signature and 4th photo slot correctly hidden).
- **Zero console/page errors** asserted across both runs.

---

## 7. Gift 3 — Step 7, the "Our Story" Book

Gift 3 is a ten-page flip book with a leather cover. It was added as **Step 7**, which
pushed *Generate & Share* to **Step 8** (`const totalSteps = 8`). Boy side only — the
girl Gift 3 templates are untouched and have no wizard step.

### 7.1 Database

`2026_08_18_000000_add_gift3_data_to_birthday_cards_table.php` adds `gift3_data` (json,
nullable). The migration is **guarded with `Schema::hasColumn`**: an earlier, since-reverted
attempt at Gift 3 left the column behind on existing databases while deleting its migration
file, so the column can already exist here even though a fresh install still needs it created.

`gift3_data` shape:

```jsonc
{
  "theme": 1-4,
  "photos": ["p1", …, "p5"],       // all five are required
  "dream_count": 3 | 4,
  "eyebrow": "Our Story",           // …and one key per text slot, see 8.2
  "date1_value": "2020-03-12",      // ISO, from a date picker
  "dream1_done": true               // checklist tick states
}
```

### 7.2 Backend

| Piece | Detail |
|---|---|
| Route | `POST /client/card/step7` → `client.card.step7` |
| Controller | `BirthdayCardController@saveStep7`, advances `current_step` to 8 |
| Uploads | `birthday-cards/gift3/` — five photos, merged not replaced, as with Gifts 1-2 |
| Model | `gift3_data` added to `#[Fillable]` and cast to `'array'` |

Field names live in **one place**, `GIFT3_TEXT_LIMITS`, which the save endpoint validates
against, the dashboard reads for its `maxlength` attributes, and the templates read off the
query string. `gift3TextKeys()` exposes the key order.

### 7.3 Dashboard — a book-page sub-wizard

Thirty-odd inputs on one panel would have buried the step, so Step 7 walks the book **one
page at a time**:

- 4 theme thumbnails — real iframes of `/boy/page/3/{giftScreenVariant}/gift/3/{1-4}`.
- A progress strip of 10 dots; one `.book-page-panel` visible at a time, with
  *Previous page* / *Next page*.
- **Continue** only appears on the last book page, so the whole book gets walked through.
- A live preview under the fields that **jumps to the page being edited** — the templates
  take a `preview_page=N` parameter that opens the book straight to that page.
- All five photos are required before Continue will save.

Gift 3 renders in a **portrait** iframe (460×723, `.gift3-thumb` / `.gift3-preview`) rather
than the 900×562 the other steps use: the book is sized off viewport *height*, so in a
landscape frame it shrank to a sliver in the middle.

### 7.4 Templates

All 8 boy files (`boy-page-3-variant-{1,2}-gift-3-page-{1..4}`) — the four "themes" differ
only in their colour tokens, so their markup and script are identical and every change is
applied to all 8. The closed cover is deliberately **not** editable.

---

## 8. Gift 3 — Interactions and Design-Safe Text

### 8.1 What changed on the page

| Book page | Change |
|---|---|
| Cover | The inside face was a blank slab for the whole 1.4s swing; it now carries a small "Our Story" plate. |
| 5 — Letter | Paper sheet enlarged and anchored top-and-bottom; paragraphs get a small margin instead of a full blank line; font auto-steps down (`.sm` / `.xs`) as the letter gets longer. |
| 6 — Dates | Each row takes an ISO date from a **date picker** and prints it long-form (`12 Mar 2020`). Free text entered before this change still renders as typed. |
| 7 — Dreams | Now **3 or 4** items (`dream_count`), not a fixed 5. Defaults: First Coffee ✓, First Selfie ✓, First Trip ✓, Grow Old Together. |
| 8 — Quote | Written out a character at a time, with a blinking caret and a longer pause on punctuation. Height is reserved up front (after `document.fonts.ready`) so the page doesn't jump. |
| 9 — Ribbon | A **Click to Open** button beside the hint; the ribbon itself is clickable too. Page text is kept to the clear column left of the ribbon. |
| 10 — Final | **Close the Book** added beside Replay Story. |

**Replay Story** closes the cover, resets every interactive page, then re-opens on page one —
the whole opening plays again from where the story was first opened. **Close the Book** does
the same but stays shut, leaving the cover tappable. Closing is just `.open` coming off
`.book`, so the same 1.4s cover transition runs backwards.

### 8.2 Design-safe limits

These are fixed layouts, not documents, so **every text field has a ceiling derived from the
room its own slot has** at the smallest size the page renders at — measured in a browser, not
guessed. One generic limit was deliberately avoided. The dashboard shows a live `n/max`
counter on each field, and `maxlength` stops input at the ceiling.

Defined in `BirthdayCardController`:

| Constant | Fields |
|---|---|
| `WELCOME_LIMITS` | heading 40, message 160 (+ 4-line cap) |
| `GIFT2_LIMITS` | name 14, signed 30, note **180 boy / 300 girl** — the ceiling follows the theme, because the boy design uses a small fixed note panel and the girl one a taller scrollable letter sheet |
| `GIFT3_TEXT_LIMITS` | 30 slots, below |
| `GIFT3_LETTER_MAX_LINES` | 10 — characters alone don't bound the letter's height |
| `GIFT3_MIN_DREAMS` / `GIFT3_MAX_DREAMS` | 3 / 4 |
| `ENDING_TEXT_LIMITS` | 7 slots, see section 11.4 |
| `ENDING_LETTER_MAX_LINES` | 14 — as with Gift 3's letter, characters alone don't bound the height |

Gift 3, by book page:

| Page | Field(s) | Limit | Why |
|---|---|---|---|
| 1 | `eyebrow` | 28 | uppercase display face with 4px tracking |
| 1 | `from_name`, `to_name` | 18 | script face at up to 42px |
| 2 | `caption` | 45 | italic line under a full-width photo |
| 3 | `memory_text` | 70 | narrow half-width column beside the photo |
| 4 | `polaroid_label` | 32 | page label |
| 4 | `note1`-`note3` | 18 | handwritten strip under a polaroid |
| 5 | `letter_label` | 32 | page label |
| 5 | `letter` | 280 + 10 lines | plus the auto font step-down |
| 5 | `envelope_hint` | 28 | |
| 6 | `dates_label` | 32 | |
| 6 | `date1_name`-`date4_name` | 22 | shares its row with the date itself |
| 7 | `dreams_label` | 32 | |
| 7 | `dream1`-`dream4` | 24 | row width minus the tickbox |
| 8 | `quote` | 120 | |
| 9 | `secret_label` | 32 | |
| 9 | `secret_button` | 20 | pill button |
| 9 | `secret_message` | 48 | |
| 10 | `final_line1`, `final_line2` | 42 | |
| 10 | `replay_label`, `close_label` | 20 | pill buttons |

Newlines are normalised to `\n` (`normaliseNewlines()`) on the way into the database for the
welcome message, the Gift 2 note, the Gift 3 letter and the ending-page letter.

---

## 9. Bug Fixes (Gift 3)

**The ribbon could never move.** `.bookmark` was `height: 100%` of `.bookmark-track`, so
`trackHeight()` — `track.clientHeight - bookmark.clientHeight` — was always **0**. Every drag
clamped to `translateY(0)` and the secret page could not be opened at all. The bookmark is now
58% of an 80% track, giving a real pull range (~190px on a phone), and there is a click
fallback besides.

**The letter overflowed its own paper, on the default text.** The sheet's content box was
~93px tall against ~150-200px of text. Enlarged, anchored top-and-bottom so it lands in the
same place at any book size, paragraphs spaced with a margin rather than a blank line box,
`overflow: hidden` as a hard backstop, and a font that steps down with length.

**Paragraph splitting missed CRLF.** Browsers submit textareas with `\r\n`, so
`preg_split('/\n{2,}/')` never matched a saved letter and blank lines went back to costing a
full line. The split is now newline-agnostic and the stored value is normalised.

**The last page painted on top at load.** Pages are absolutely stacked and only `.current`
carries a z-index, so before the first flip the last page in the DOM won. Page one is now
marked current at init.

**Quote height measured before the webfont loaded**, giving a wrong reserved height; it is
now measured on `document.fonts.ready`.

---

## 10. Testing Performed

Real browser automation (headless Chromium via Playwright) plus `curl`, not inspection:

- **Blade compile + PHP lint** on all 8 templates, the controller and the dashboard.
- **`curl` per template** — no params (defaults render unchanged), and with overrides
  (every parameter takes effect, ISO dates format, `dream_count` honoured).
- **Full wizard run, Steps 1-8** as a real client: theme → PIN → welcome → gift box →
  Gift 1 → Gift 2 → Gift 3 → Generate. Asserted the new limits are present, the date
  pickers emit ISO, the letter's line cap trims, the dreams list adds/removes and shifts
  rows up without leaving a hole, refuses to go below 3 or above 4, and that Continue is
  blocked until all five photos are in.
- **Resume after reload** — theme, five photos, dates, dream count, letter (LF only) and
  the character counters all come back.
- **Letter fit** across three viewports at 5 lengths, including 10 blank-ish lines.
- **Ribbon, quote typing, close and replay** driven and asserted; replay stays on the same
  URL and lands back on page one.
- **Zero console/page errors** across every run.

### Known limitation

A final sweep that filled **every field on a page to its exact limit at once** still found
minor overflow in three places, all at the largest render (the 460×723 preview iframe) and
only with every field simultaneously maxed:

| Page | Overflow |
|---|---|
| 1 — Title | `.heart-deco` pushed ~6-13px past the page at the smallest viewports |
| 6 — Dates | ~45px when all four titles are 22 chars *and* all four dates are set |
| 8 — Quote | ~86px at the full 120 characters |

Ordinary content is well clear of this, but the three limits above want tightening (bisecting
the true safe length per field is the way to do it). Nothing else in the sweep overflowed;
the other hits were the confetti and floating-heart decorations, which drift outside the page
by design.

---

## 11. Ending Page — Step 8

The story's closing screen: an envelope that opens into a handwritten letter. The four
`page-4` templates already existed but were static and unwired; they are now data-driven
and reachable from the dashboard, boy side fully integrated.

### 11.1 Database

`2026_08_18_010000_add_ending_and_qr_data_to_birthday_cards_table.php`

| Column | Type | Purpose |
|---|---|---|
| `ending_data` | `json` nullable | Step 8 — ending design + all seven text slots |
| `qr_data` | `json` nullable | Step 9 — chosen QR design + when it was generated |

The same migration **drops `ending_variant` and `ending_message`** — the orphan columns
left by the reverted attempt at this feature. No migration ever created them, nothing read
them, and every row had them null. `ending_data` replaces them because the ending page has
seven text slots, not one.

```jsonc
// ending_data
{
  "theme": 1-4,
  "title": "One Last Thing",
  "subtitle": "Before you go, read this.",
  "tap_label": "Tap to Open",
  "letter_heading": "A Letter For You",
  "letter": "…",              // newlines normalised to \n
  "signoff": "— always yours",
  "end_label": "The End"
}
```

### 11.2 The dynamic theme registry

`BirthdayCardController::ENDING_THEMES` names the four designs **per side** and carries an
`available` flag:

```text
boy   1 Cool Steel · 2 Graphite Ice · 3 Midnight Gold · 4 Slate Emerald   available
girl  1 Warm Cream · 2 Blush Cream · 3 Rose Gold Noir · 4 Lavender Midnight   not yet
```

Nothing about the chosen design is hardcoded anywhere: the dashboard reads the registry for
its labels, the save endpoint stores the number, and the page itself is the existing route
`/{theme}/page/4/{n}`. **Turning the girl side on is a matter of flipping its four
`available` flags** — the panel, the save endpoint, the preview and the restore logic are
already side-agnostic. Until then the girl side renders a placeholder listing the four
designs that are coming, rather than an empty tab.

### 11.3 Backend

| Piece | Detail |
|---|---|
| Route | `POST /client/card/step8` → `client.card.step8` |
| Controller | `BirthdayCardController@saveStep8`, advances `current_step` to 9 |
| Limits | `ENDING_TEXT_LIMITS` (7 slots) + `ENDING_LETTER_MAX_LINES` (14) |

`endingTextKeys()` exposes the key order, so the dashboard, the validator and the templates
all read one list — the same arrangement Gift 3 uses.

### 11.4 Templates

All four boy templates (`boy-page-4`, `-4-2`, `-4-3`, `-4-4`) are now query-parameter
driven, each parameter defaulting to the template's own original text, so the pages still
render byte-for-byte unchanged with no query string.

| Param | Slot | Limit |
|---|---|---|
| `title` | envelope heading | 28 |
| `subtitle` | line under it | 48 |
| `tap_label` | uppercase tap hint | 20 |
| `letter_heading` | heading on the paper | 32 |
| `letter` | the letter itself | 500 + 14 lines |
| `signoff` | signature line | 28 |
| `end_label` | closing stamp | 20 |

`?preview_stage=letter` skips the envelope and prints the letter in full — the dashboard
preview uses it so the client can see the text they are typing without sitting through the
handwriting animation. It is the ending page's equivalent of Gift 3's `preview_page=N`.

### 11.5 Dashboard

Step 8 shows the four designs as real iframes of `/{theme}/page/4/{1-4}`, the seven fields
with live `n/max` counters, and a live preview with **Envelope / Letter** tabs — the page
opens on a closed envelope, so without the second tab the field the client spends the most
time on would be invisible.

The preview frame is **portrait (460×723)**, not the 900×562 the other steps use, for the
same reason Gift 3's book is: the ending page is a height-driven mobile-first design, and
in a landscape frame the letter sheet reads nothing like it does on a phone.

---

## 12. QR Select — Step 9

Step 9 replaces the old *Generate & Share* panel, which was a client-side stub that
fabricated a fake slug and drew a dashed placeholder box.

### 12.1 QR generation

`endroid/qr-code` (^6.1) does the **encoding**; the **drawing** is ours, in
`app/Support/QrRenderer.php`, because the themes differ in module shape, colour and framing
— things the library's own writers don't expose. Its PNG writer also needs the GD
extension, which this environment does not have, so SVG is the output format either way.

> The package was already sitting in `vendor/` from the reverted work but was not declared
> in `composer.json`, so any `composer install` would have deleted it. It is now a real
> dependency (`composer require endroid/qr-code:^6.1`), locked together with
> `bacon/bacon-qr-code` and `dasprid/enum`.

The renderer keeps the three things a scanner actually needs — the full 4-module quiet zone,
high error correction, and dark-on-light contrast — and varies only the cosmetics. The
finder patterns ("eyes") are drawn as two shapes rather than 33 modules so they can be
softened, but their corner radius stays **below one module**, so every module of the ring is
still filled at its own centre. Circular eyes were tried and rejected for exactly that
reason.

### 12.2 The four QR designs

`BirthdayCardController::QR_THEMES`, same per-side shape and `available` flag as the ending
registry:

| # | Boy | Girl (not wired up yet) |
|---|---|---|
| 1 | Midnight Navy — classic squares, ivory card | Dusty Pink |
| 2 | Steel Glow — rounded modules, blue gradient | Blush Petal |
| 3 | Graphite Ice — dark card, ice blue dots | Rose Gold Noir |
| 4 | Blueprint — dashed frame, plain squares | Lavender Line |

### 12.3 The share link

The card's `slug` is settled **when the dashboard first renders**, not at publish time
(`ensureSlug()`, called from `ClientAuthController@dashboard`). A QR encodes a URL, so the
four previews can only be honest if the address is already fixed — otherwise the previews
would encode one URL and the generated code another.

The slug is `{to-name}-{6 random chars}`, and `shareUrl()` builds `/{PUBLIC_CARD_PATH}/{slug}`
— `/c/{slug}`. **The public story flow itself is Task 3**; only the address is fixed here,
so a code generated today still points at the right place once that flow lands.

### 12.4 Backend

| Piece | Detail |
|---|---|
| Route | `POST /client/card/step9` → `client.card.step9` |
| Controller | `BirthdayCardController@saveStep9` |
| Saves | `qr_data` = `{ theme, generated_at }`, plus the slug if it was still unset |
| Returns | `slug`, `share_url`, `qr_svg` (720px) |

### 12.5 Dashboard

The four designs are rendered server-side at page load as data-URI SVGs, against the card's
own link, so picking one needs no round trip. Only the sides that are actually wired up are
rendered, so the page doesn't carry four unused QR images; when the girl designs are
switched on their previews appear automatically.

**Generate** saves the choice and returns the full-size code. From there: the link with a
**Copy Link** button, **Download PNG** and **Download SVG**. PNG is rasterised in the browser
from the same SVG markup on a canvas — no second round trip and no second source of truth.

A card that was already generated comes back with its link and code in place on reload,
rather than hiding them behind the Generate button again. Regenerating with a different
design keeps the same link.

---

## 13. Bug Fixes (Ending Page)

**`boy-page-4-3.blade.php` was corrupted** — three complete, identical HTML documents
concatenated into one 2429-line file. Verified all three copies were byte-identical, then
truncated to the single 810-line document.

**The letter overflowed its own paper on the default text.** The ending page's default
letter is 337 characters across 9 lines, which ran ~64px past the sheet on a 360px-wide
phone and ~59px in the preview frame. Fixed the same way Gift 3's letter was: the sheet now
steps the font down (`.sm` / `.xs`) as the letter gets longer, weighted so a hard line break
counts as about a line's worth of characters. Every length up to the 500-character cap now
fits at every viewport tested.

**The handwriting could be written below the fold.** `.letter-paper` scrolls, but the
typewriter never scrolled it, so a long letter was typed out of sight. It now follows the
pen.

---

## 14. Testing Performed (Steps 8-9)

Real browser automation (Playwright driving Chrome) plus `curl`, not inspection.

- **PHP lint + Blade compile** on every changed file, and `view:cache` over the whole app.
- **`curl` per ending template** — no params (defaults render unchanged on all four), and
  with overrides (every parameter takes effect, `preview_stage` honoured).
- **QR correctness, all 8 designs (boy and girl).** Each SVG was rasterised and **sampled at
  every module centre**, then compared against the encoder's own matrix: 0 mismatched
  modules out of 33×33, on every design. Contrast between dark and light modules was
  measured too, and two designs were corrected after this — the gradient themes' finder
  centres were the lightest thing on the card, at 40%/27% contrast. Every design now sits at
  45% or better.
- **Full Step 8 → Step 9 run as a real client** (54 assertions): the nine sidebar steps, the
  design registry driving the labels, thumbs pointing at the real pages, Continue blocked
  until a design is picked, defaults present, `maxlength` and counters from the controller,
  the live preview following what is typed, the Envelope/Letter tabs, the 14-line cap,
  saving, the four QR previews, Generate blocked without a design, the generated link, the
  rendered code, **PNG and SVG downloads actually saving files** (159KB / 25KB), Copy Link
  reaching the clipboard, and the girl side falling back to its placeholder on both steps.
- **Resume after reload** — title, sign-off, letter, both chosen designs, the character
  counters, and an already-generated link and code all come back. The link is stable across
  reloads and across regenerating with a different design.
- **Server-side limits** — the save endpoint returns 422 for a title past its ceiling and
  for a letter past its line cap.
- **Design-safe sweep** — every ending field filled to its exact limit at once, across four
  viewports × four designs × both stages (32 cases): no overflow, no clipped line, no
  scrolling sheet.
- **Zero console/page errors**, other than the two deliberate 422s.

---

## 15. Not Included

*(As of section 17 the public story is built — this list is superseded by section 19.)*

- **Girl Gift 3, ending page and QR** — the girl Gift 3 templates are untouched, and the
  girl ending/QR designs are registered but flagged unavailable. Steps 7-9 are boy-only.
- **Publishing** — `is_published` is still never set.

---

## 16. Environment Notes

- **Shell `DB_*` environment variables override `.env`.** The shell profile exports
  `DB_CONNECTION=pgsql`, `DB_HOST=postgres`, `DB_DATABASE=munsif` (a different project),
  which take precedence over `.env` and cause `php artisan` to fail with a connection error.
  Workaround when running artisan commands:
  ```bash
  env -u DB_CONNECTION -u DB_HOST -u DB_PORT -u DB_DATABASE -u DB_USERNAME -u DB_PASSWORD \
    php artisan migrate:status
  ```
- **No GD extension.** Anything that needs to produce a raster image server-side will fail;
  the QR code is SVG for this reason, and PNG is produced in the browser.
- **Some uploaded photos were lost.** During earlier test-data cleanup, the gift/profile
  upload folders were cleared wholesale rather than by specific filename, which deleted real
  photos belonging to existing cards (user IDs 2 and 8). The database rows still reference
  those paths, but the image files are gone and must be re-uploaded.

*(Resolved since the last revision: `vendor/endroid/` is no longer orphaned — see 12.1;
`ending_variant` / `ending_message` are dropped — see 11.1; `boy-page-4-3.blade.php` is no
longer corrupted — see 13.)*

---

## 17. The Public Story — What the Link Opens

The other half of the loop: the card a recipient actually sees when they open the generated
link or scan the QR. Boy is fully wired; the girl side is turned away rather than rendered
half-configured.

```text
Dashboard → save configuration → generate link/QR
      ↓
/c/{slug}  →  lock  →  welcome  →  gifts  ⇄  gift 1 / gift 2 / gift 3  →  ending
```

### 17.1 One route per client, not one global story

Every card already carries its own `slug` (settled in Step 9 — see 12.3), so a single set of
routes serves every client and each link opens only that client's configuration:

| Route | Name | Page |
|---|---|---|
| `GET /c/{slug}` | `story.lock` | Page 1 — the lock screen |
| `POST /c/{slug}/unlock` | `story.unlock` | code check |
| `GET /c/{slug}/welcome` | `story.welcome` | Page 2 |
| `GET /c/{slug}/gifts` | `story.gifts` | Page 3 — gift selection |
| `GET /c/{slug}/gift/{1,2,3}` | `story.gift` | the three gifts |
| `GET /c/{slug}/ending` | `story.ending` | the ending page |

An unknown slug is a 404. A girl card is a 404 too, for now — the dashboard does not
configure the girl side, so there is nothing honest to render. Adding `'girl'` to
`PublicStoryController::LIVE_THEMES` is what turns it on; the whole mapping below is already
theme-agnostic.

### 17.2 There is no second copy of the story

`PublicStoryController` looks the card up by slug, turns its stored JSON into the **same
query parameters the dashboard's live preview passes**, merges them into the request, and
renders the **same template**. The templates already read their content with
`request('key', default)`, so nothing new had to be taught to them.

| Page | Template | Comes from |
|---|---|---|
| Lock | `boy-page-1{-2}` | `variant`, `profile_image_path`, `lock_code` |
| Welcome | `boy-page-2{-2}` | `variant`, `heading`, `welcome_message` |
| Gifts | `boy-page-3{-2}` | `gift_screen_variant` |
| Gift 1 | `…gift-1-page-{n}` | `gift1_data` — design + 3 photos |
| Gift 2 | `…gift-2-page-{n}` | `gift2_data` — design, 4 photos, names, `cal_day`, note, signature |
| Gift 3 | `…gift-3-page-{n}` | `gift3_data` — design, 5 photos, all 30 text slots, dates, tick states, dream count |
| Ending | `boy-page-4{-2,-3,-4}` | `ending_data` — design + 7 text slots |

Because the inputs are identical, **the public page and the dashboard preview are the same
page**. Gift 2's `cal_day` is derived from the stored `cal_date` exactly as the dashboard
preview derives it, so the calendar marks the same day the client saw.

### 17.3 Navigation is injected, not baked in

The card designs are standalone HTML documents with no layout and no shared partial, and the
dashboard previews them by loading these very URLs in an iframe. Writing story navigation
into them would mean editing thirty-odd files and would leave dead Next buttons in every
dashboard preview.

So `App\Support\StoryChrome` adds it to the rendered HTML instead, just before `</body>`.
Each snippet is self-contained and only ever adds; where a design already has the right
control, the snippet wires that one up rather than drawing a second:

| Page | What the chrome does |
|---|---|
| Lock | Turns the decorative numpad into a real 4-digit entry, drops the client's photo into the frame, adds the error line. Matches all three designs' class names (`.boy2-*`, `.girl-*`, `.girl2-*`) |
| Welcome | Points the design's own **NEXT** button at the gift screen |
| Gifts | Redefines `openGiftPage(n)` — which pointed at a URL that was never built — keeping the design's loading flourish |
| Gift 1 / 2 | A floating **Next →** back to the gift screen |
| Gift 3 | A floating **← Gifts** and **One Last Thing →** |
| Ending | A floating **← Back to the gifts** |

Verified: none of this chrome appears on the standalone `/boy/page/…` preview URLs.

> The book's onward link is floating rather than a third button in its own Replay / Close row.
> It was tried there first and the cover sits over that area in the stacking order once the
> book is shut, so the button was not reliably clickable.

### 17.4 The lock screen

The design shipped as pure decoration — four empty boxes and a numpad of dead buttons. It now
takes a real code:

- `✱` clears the last digit, `#` submits, a fourth digit submits on its own, and the keyboard
  works too.
- **The code is checked on the server** (`hash_equals`) and never reaches the page, so it
  cannot be read out of the source.
- A wrong code shakes the row, shows an error, clears the entry, and stays put.
- A right code puts an unlock flag in the session, keyed by card id, and every later page
  redirects back to the lock screen without it. Unlocking one story does **not** unlock
  another.

---

## 18. Testing Performed (Public Story)

Playwright driving Chrome, plus `curl`.

- **Full boy flow, 39 assertions** — link opens the lock screen with the client's own photo,
  placeholder digits cleared, the code absent from the source; a wrong code errors and stays;
  the right code opens the welcome page with the saved heading and message; NEXT reaches the
  gift screen; each gift opens, shows **its own configured photos and text**, and comes back;
  the book carries its quote, dates, dreams, letter and all five photos, opens, walks to its
  last page, and leads on to the ending; the ending renders **the design that was selected**
  with its configured text, and its envelope opens into the letter being written out.
- **Per-client isolation, 20 assertions** — two clients, two different links, different
  codes, different designs on every page. Story A's code is rejected by story B; each link
  shows only its own welcome text, gift 2 names, book quote and ending design; unlocking one
  does not unlock the other; an unknown slug is a 404.
- **Responsive** — the whole flow driven on a 390×844 phone: the numpad is usable, the gift
  screen switches to its mobile layout, the mobile gift boxes open the right gift, and the
  page never scrolls sideways.
- **Nothing else broken** — all eleven standalone preview routes still return 200 (boy and
  girl, every page and gift), and the injected chrome is confirmed absent from them.
- **Girl** — a girl card 404s on every story route rather than erroring.
- **Zero console/page errors**, other than the deliberate wrong-code 422 and unknown-slug 404.

---

## 19. Still Not Included

- **Girl public story** — templates exist and the mapping is theme-agnostic, but the
  dashboard does not configure the girl side yet, so the routes turn a girl card away.
- **Girl Gift 3, ending page and QR** in the dashboard — registered, flagged unavailable.
- **Publishing** — `is_published` is still never set. The slug and link exist and work, but
  nothing marks a card finished, so `currentDraft()` keeps returning the same card for
  editing and re-generating.
- **`lock_hint`** — the column exists and the designs show a hardcoded hint line; it is not
  yet configurable from the dashboard.

---

## 20. The Girl Side — Pages 1-3, Gift 1 and Gift 2

The boy side was built first and, in a couple of places, its shape had been applied to the
girl designs as though they were the same screens in different colours. They are not. This
section is the girl side catching up: Pages 1-3 made dynamic, Gift 1 given the fields its
design actually has, and Gift 2 given an editor that matches its scene.

**The boy side is untouched by this work** — see the regression run in 21.

### 20.1 Pages 1-3

| Page | State before | Now |
|---|---|---|
| 1 — Lock | Design 2 took a `photo`; design 1 drew an illustration with no way in | Design 1 takes `photo` too, with the illustration kept as the fallback when there isn't one |
| 2 — Welcome | Already took `heading` and `message` | Unchanged |
| 3 — Gift screen | Both designs already offered and saved in Step 4 | Unchanged |

So the only real gap was the lock screen's photo, which meant a girl client uploaded a
picture in Step 2 and then couldn't see it in the preview. `girl-page-1` now renders the
upload inside its oval frame and falls back to the original artwork when no photo is set —
the template still renders byte-for-byte as before with no parameters.

### 20.2 Gift 1 — the girl design's own fields

The girl Gift 1 templates were **already** parameter-driven (`photo1-3`, `cal_month`,
`cal_day`, `message`). What was wrong was the dashboard: Step 5 offered three photo slots and
nothing else, because that is all the *boy* design has. A girl client could pick one of the
four girl themes and then had no way to set the calendar it prints or the note beside it.

Step 5 is now theme-aware, the same way Step 6 already was:

| Field | Boy | Girl |
|---|---|---|
| Photo slots | 3 | 3 |
| Special Date | hidden | shown |
| Handwritten Note | hidden | shown |

- **One date picker drives the whole calendar.** `cal_month`, the marked day, and the number
  of cells the grid draws all come from it. The grid used to be hardcoded to 31 days, so a
  February date rendered three days that don't exist; it now takes `cal_days`.
  `BirthdayCardController::girlCalendarParams()` derives the three values for the public
  story, and the dashboard derives the same three in JavaScript for its live preview.
- **The note** gets `GIFT1_GIRL_LIMITS['message']` — 90 characters, the room the small panel
  beside the calendar actually has.
- The girl fields are accepted and stored on a boy card too, so a client who fills them in
  and then switches theme keeps their work; the boy templates simply have nowhere to show it.

### 20.3 Gift 2 — a scene, walked one beat at a time

The girl Gift 2 is not a form's worth of fields: it is a wrapped box that opens onto three
polaroids and then an envelope. Putting all of it on one panel would have buried the step, so
it is walked in **five beats**, the same shape Gift 3's book already uses:

| Beat | Fields | Limit |
|---|---|---|
| 1 — The Gift Box | Line above the box (`box_title`) | 34 |
| | Prompt under it (`box_hint`) | 20 |
| 2 — Photo 1 | photo + caption (`cap1`) | 18 |
| 3 — Photo 2 | photo + caption (`cap2`) | 18 |
| 4 — Photo 3 | photo + caption (`cap3`) | 18 |
| 5 — The Letter | `message` | 300 |

- A progress strip of five dots, **Previous / Next** between beats, and **Continue** held
  back until the last one so the whole scene gets walked through. (This is the missing Next
  button — before, the girl side had no way through the step at all.)
- The two sides now have **separate editors**: `#gift2BoySide` is the original single form,
  `#gift2GirlSide` the five beats, and only one is ever shown.
- Each beat carries its own photo slot, driven by the *same* file input as the boy form's, so
  `mirrorGiftPhotoSlot()` keeps the two thumbnails in step rather than duplicating the upload.
- The letter box appears in both editors and mirrors itself (`onGift2MessageInput`), so
  switching theme never loses what was written. Its ceiling still follows the design —
  180 boy, 300 girl.
- The live preview passes every new parameter, so what the client sees while typing is what
  the page renders.

Six new parameters were added across the eight girl Gift 2 templates: `box_title`,
`box_hint`, `cap1`, `cap2`, `cap3`. As everywhere else, each defaults to the template's own
original wording, so the pages still render unchanged standalone.

### 20.4 Storage

No migration. Both blobs gained keys inside the JSON they already have:

```jsonc
// gift1_data — the last two are girl designs only
{ "theme": 1-4, "photos": [...], "cal_date": "2026-02-09", "message": "…" }

// gift2_data — the last five are girl designs only
{ …, "box_title": "…", "box_hint": "…", "cap1": "…", "cap2": "…", "cap3": "…" }
```

---

## 21. Testing Performed (Girl Side)

Playwright driving Chrome, plus `curl`.

- **Full girl wizard run, 55 assertions** — Steps 1-6 as a real client: the lock preview is
  the girl design; all four Gift 1 themes are girl pages; the calendar and note fields appear
  and carry their limits; the preview passes the month name, the marked day and **February's
  real 28 days**; Gift 2 shows the five-beat editor with its dots, holds Continue back to the
  last beat, drops each photo into its own beat slot, and passes every caption, both box
  lines and the letter to the preview; everything comes back after a reload.
- **Design-safe limits, 64 cases** — every new girl field filled to its exact ceiling, across
  both gift-screen variants, all four designs each, at 360×640, 390×844, the preview frame and
  desktop. Nothing overflows, nothing is clipped, no page scrolls sideways, and the calendar
  draws the right number of days with one marked.
- **Girl lock screens, 18 assertions** — both girl designs driven end to end with the girl
  side temporarily enabled: the boxes start empty, the client's photo lands in the oval and
  arch frames, the code stays out of the page source, a wrong code errors and stays put, and
  the right one opens the welcome page. The flag was reverted afterwards.
- **Boy regression, 17 assertions** — a full boy run through Steps 1-6: the girl fields stay
  hidden, the boy Gift 2 form is the one shown with its fourth photo slot and name fields,
  Continue is available immediately, the previews carry every boy parameter and **none** of
  the girl ones, saving still works, and the 180-character boy ceiling is intact.
- **Public story unaffected** — the boy story suite (40 assertions) and the per-client
  isolation suite (20) both still pass in full.
- **All 80 template routes still return 200** — boy and girl, every page and every gift.
- **`curl` per template group** — no parameters (defaults render unchanged) and with
  overrides (every new parameter takes effect).
- **Zero console/page errors** across every run.

> While hardening these runs, the lock-screen assertions were changed to wait on the actual
> outcome rather than a fixed delay. The unlock is a server round trip, and on a slow dev
> server a stopwatch made the suite flaky — the behaviour itself was correct.

---

## 22. Girl Side — Still To Do *(superseded by section 26)*

- **Gift 3** — the girl book templates are untouched; Step 7 remains boy-only.
- **Ending Page and QR Select** — the girl designs are registered in the theme tables and
  flagged `available => false`, so both steps show their placeholder.
- **The public girl story** — `PublicStoryController::LIVE_THEMES` is still `['boy']`, so a
  girl link 404s. Everything under it is ready: the parameter mapping is theme-agnostic and
  now covers every girl Gift 1 and Gift 2 field, and the lock screen's injected wiring matches
  all three designs' class names (`.boy2-*`, `.girl-*`, `.girl2-*`) — both girl lock designs
  were driven end to end to prove it, then the flag was put back. What is left before that
  flag can flip is girl Gift 3 and the girl ending page.

---

## 23. Girl Gift 3, Ending Page and QR Select — and the Boy Welcome Bug

The girl side finished: its Gift 3, its own ending design, its own QR set, and the girl
public story turned on. Plus one boy bug, in section 27.

### 23.1 Girl Gift 3 — the camera roll

The girl Gift 3 is not a book. It is a phone with a camera roll on it, scrolled card by
card: photos with a date and a place, two video clips, a chat screen, a taped-up note, and
a letter that opens full screen. All eight cards came from a hardcoded `MEMORY_DATA` array
in the template; that array is now built in Blade from query parameters, each defaulting to
the design's own original content.

The dashboard walks it in **nine beats**, the same shape Gift 3's book and Gift 2's scene
already use:

| Beat | Card | Fields |
|---|---|---|
| 1 | The Cover | `cover_title`, `cover_sub`, `cover_tap`, `gallery_title` |
| 2 | Photo 1 | photo + `p1_date`, `p1_place`, `p1_caption` |
| 3 | Video Clip 1 | cover still + **clip upload** + `v1_duration`, `v1_date`, `v1_place`, `v1_caption` |
| 4 | The Chat | `chat_name`, `chat1`, `chat2`, `chat3`, `chat_date`, `chat_caption` |
| 5 | Photo 2 | photo + `p2_date`, `p2_place`, `p2_caption` |
| 6 | The Pinned Note | `note_date`, `note_text` (3-line cap) |
| 7 | Video Clip 2 | cover still + **clip upload** + `v2_*` |
| 8 | Photo 3 | photo + `p3_date`, `p3_place`, `p3_caption` |
| 9 | A Love Letter | `letter` (12-line cap), `signoff` |

- **Video is a new kind of upload.** Two clips, stored under `birthday-cards/gift3-video/`,
  validated on their real MIME type (`video/mp4`, `webm`, `ogg`, `quicktime`) with a 20 MB
  ceiling. Each slot shows a frame of the chosen clip, and the card plays it on tap.
  > **Deployment note:** PHP's own `upload_max_filesize` and `post_max_size` must be raised
  > to at least 20 MB. They ship at 2 MB and 8 MB here, and a request over them never
  > reaches Laravel at all.
- **Storage.** The five image slots reuse the existing `photos` array — slots 0-2 are the
  photo cards, 3-4 the two video posters — so all the existing merge-not-replace upload
  handling applies unchanged. `videos` is a new two-slot array beside it. `saveStep7`
  branches on theme and merges over whatever the other side had, so switching theme
  mid-build never throws work away.
- **Escaping.** The cards are built with `innerHTML` from a template literal. Client text
  now goes through an `esc()` helper first — before this, an apostrophe in a caption broke
  the attribute it sat in.

### 23.2 The live preview follows the beat

The girl Gift 2 and Gift 3 designs both open on a closed thing — a wrapped box, a camera-roll
cover — and only reveal the rest as the recipient taps through. The preview sat on that first
screen the whole time, so a client filling in photo 2 or the letter never saw what they were
making. Both templates now take a preview parameter, exactly as the boy book's
`preview_page` already did:

| Design | Parameter | Effect |
|---|---|---|
| Girl Gift 2 | `preview_stage=photos` | Skips the box, shows all three polaroids at once with their captions |
| | `preview_stage=letter` | …and opens the envelope with the letter already written |
| Girl Gift 3 | `preview_card=N` | Opens the roll, reveals every card, scrolls to card N |

The dashboard sets them from whichever beat is on screen, so the preview moves with the
client. Both fast-forwards reveal *all* the cards or photos at once rather than staging them
in one at a time, because the staging is what hid the thing being edited.

### 23.3 A Girl ending page — "The Bloom"

The girl ending templates were a recolour of the boy's envelope. They have been replaced
with a design of their own: a closed flower sits on a soft field, and tapping it unfurls ten
petals to reveal a **round keepsake card** that writes the note out by hand, with petals
drifting past throughout.

Four colourways, same markup, colour tokens only — the convention the other four-theme sets
use:

| # | Name | |
|---|---|---|
| 1 | Blush Rose | blush petals on cream |
| 2 | Lilac Dusk | lavender petals on soft violet |
| 3 | Rose Gold Noir | dark field, rose gold bloom |
| 4 | Plum Midnight | midnight field, plum bloom |

The seven text slots line up one-for-one with the boy ending's, but their wording, their
ceilings and the half of the page a preview should show all differ. So Step 8 keeps **one**
set of inputs and swaps their labels, `maxlength` and defaults with the theme
(`ENDING_SIDE_META` / `applyEndingSide`). A value the client actually typed is left alone; one
still sitting at the other side's default is replaced. The preview tabs become **Flower /
Note**, and the Note tab passes `preview_stage=note`.

### 23.4 Girl QR Select

The four girl QR designs are now live and are their own family, not the boy set in pink:
rounded and dot modules throughout where the boy designs use hard squares, blush and lilac
palettes, and a **double card border** the boy set never uses — a new `frame: 'double'` in
`QrRenderer`.

| # | Name | Modules | Frame |
|---|---|---|---|
| 1 | Blush Petal | rounded | double, blush |
| 2 | Lilac Confetti | dots | none |
| 3 | Rose Gold Noir | dots on a dark card | double |
| 4 | Plum Midnight | rounded on deep plum | solid |

Generation, the link, copy and both downloads run through the same endpoint and the same
flow as the boy side — nothing was duplicated.

### 23.5 The girl public story

`PublicStoryController::LIVE_THEMES` is now `['boy', 'girl']`. What the girl side needed
beyond the existing mapping:

- `gift3GirlParams()` beside `gift3Params()` — the two Gift 3 designs are different objects,
  so they take different parameters.
- The ending's keys come from `endingLimits($card->theme)` rather than one fixed list.
- `StoryChrome::welcome` matches `.bb-next, .gb-next` — each side names its NEXT button
  differently.

The lock screen already matched all three designs' class names from the previous round.

---

## 24. Design-Safe Limits (Girl Gift 3 and Ending)

Every new slot's ceiling was measured in a browser, not guessed — and the measuring turned
up four real layout bugs first.

| Constant | Notable slots |
|---|---|
| `GIFT3_GIRL_TEXT_LIMITS` | 30 slots; captions **34**, chat bubbles 44, note 90 (+3 lines), letter 420 (+12 lines) |
| `GIFT3_GIRL_NOTE_MAX_LINES` / `GIFT3_GIRL_LETTER_MAX_LINES` | 3 / 12 |
| `GIFT3_GIRL_VIDEO_MAX_KB` | 20480 |
| `ENDING_GIRL_TEXT_LIMITS` | title 24, subtitle 40, tap 18, heading 26, **note 180**, signoff 22, closing 18 |
| `ENDING_GIRL_LETTER_MAX_LINES` | 8 |

The girl ending's note is capped well below the boy's 500 because a circle holds far less
than a rectangular sheet, and the keepsake card does not scroll.

### Bugs the sweep found

**The keepsake card's padding was a percentage.** Percentage padding resolves against the
*containing block's* width, not the element's — so at 1440px wide, `15%` was 210px a side on
a 420px card and left the note zero content width. It collapsed to one character per line
and ran 3000px tall. Both padding and width are now derived from one `--keep-size` length.

**Its children shrink-wrapped.** The note, heading, sign-off and closing line are centred
flex children with no width, so a long line pushed straight out of the card and gave the page
a sideways scroll. They now fill the padded box.

**The camera roll silently ate captions.** `.meta-caption` was one line with
`text-overflow: ellipsis`, which cut anything past ~20 characters — including the design's
own 32-character defaults. It now runs to three lines, so a caption of the length the design
itself ships is shown in full.

**The bud's wrapper scaled past the viewport.** `.bloom-wrap.gone` scales to 1.35 while
fading out, reaching outside the page on every side; `.stage` now clips it.

The auto-fit step-downs on the keepsake note were re-tuned against the measurements
(`sm` above weight 72, `xs` above 140) so every length from empty to the cap fits.

---

## 25. Testing Performed (Girl Gift 3, Ending, QR, Public Story)

Playwright driving Chrome, plus `curl`.

- **Girl dashboard run, 55 assertions, three consecutive clean runs** — Steps 1-9 as a real
  client: the camera-roll editor replaces the book, nine cards, Continue held to the last;
  photos and **both video clips** upload and preview; the live preview carries the cover
  title, captions, chat text, clip length, note, letter, photos and clips; the ending step is
  live with the four bloom designs, girl defaults, the 180 ceiling and Flower/Note tabs; the
  four girl QR designs render, generate a link, copy it and download as PNG and SVG;
  everything comes back after a reload.
- **Design-safe limits, 64 cases** — every new girl slot filled to its exact ceiling *at the
  same time*, across both gift-screen variants, all four designs each, at 360×640, 390×844,
  the preview frame and desktop. Nothing overflows, nothing is clipped, no page scrolls
  sideways.
- **QR scannability, all 8 themes** — each rendered SVG rasterised and sampled at every
  module centre against the encoder's own matrix: zero mismatched modules and ≥45% contrast
  on all eight. Two girl finder rings were darkened after the first pass measured 41% and
  48%.
- **Girl public story, 26 assertions** — the link opens the girl lock screen, the code is not
  in the source, a wrong code is rejected, the right one opens the welcome page, NEXT reaches
  the gifts, each gift opens with its configured content, the roll carries every configured
  card *and the uploaded clips*, and the ending is **girl design 3's bloom**, which opens into
  the keepsake card with the configured note.
- **Boy regression, 18 assertions on both designs** — lock, welcome, NEXT, gift 1 out and
  back, the story book, on to the ending, and the envelope still opening into the letter.
- **All 80 template routes still return 200**, boy and girl, every page and gift.
- **Zero console/page errors** other than the deliberate wrong-code 422.

---

## 26. Girl Side — What Is Left

Nothing on the girl feature list. Both sides are now configured in the dashboard and both
public stories run.

Still open across the project:

- **Publishing** — `is_published` is never set. Slugs and links exist and work, but nothing
  marks a card finished, so `currentDraft()` keeps returning the same card for editing.
- **`lock_hint`** — the column exists and the designs show a hardcoded hint line; it is not
  configurable from the dashboard.
- **Video upload size in the deployment** — see the note in 23.1.

---

## 27. Boy Public Welcome Page — Next Button Fix

**Symptom.** On the boy public story, after the right code, the Welcome page's **NEXT**
button did nothing on welcome design 2 (`boy-page-2-2.blade.php`).

**Cause.** Not the button and not the injected navigation — a decorative element on top of
it. `.bb-glow` is a 420px circle centred on the card with no `pointer-events` rule, so it
covered the NEXT button and swallowed every click before it arrived. Design 1
(`boy-page-2.blade.php`) marks the same glow `pointer-events: none`; design 2 never did.

**Fix.** `pointer-events: none` on design 2's three decorative layers — `.bb-glow`, the
`.bb-ember` particles and the `.bb-corner` flourishes — matching what design 1 already does.
Purely additive CSS: nothing about the design's appearance changes.

**Verified.** Both boy welcome designs driven end to end: lock → code → welcome → NEXT →
gift selection → gift → ending → letter. The lock flow, the welcome page, the design and the
rest of the story navigation are untouched.

---

## 28. Step 8 Save Errors Were Silent, Plus a Dead Function

**Symptom.** Clicking **Continue** on the Ending Page (Step 8) could return a `422` from
`POST /client/card/step8`, and the dashboard only ever showed a generic "Could not save.
Please try again." — with no indication of which field was wrong or why, so retrying the
same input just failed again.

**Cause.** The girl ending design's text limits are deliberately tighter than the boy's — the
round keepsake card holds less than the envelope's sheet, so `letter` is capped at **180
characters / 8 lines** versus the boy's 500/14 (`ENDING_GIRL_TEXT_LIMITS`, section 11.4 /
23.3). That server-side `422` was always correct behaviour, but `saveStep8AndContinue()` in
`resources/views/client/dashboard.blade.php` threw away the response body on failure and
showed the same generic message regardless of cause, so a client over a limit had no way to
tell what to shorten.

**Fix.**
- Added `firstValidationError(body)`, a small helper that reads Laravel's `422` JSON shape
  (`{ message, errors: { field: [msg, ...] } }`) and returns the first field's first message.
- `saveStep8AndContinue()` now parses the response body on a non-`ok` result and surfaces that
  real message in the step's error banner (e.g. "The letter must not be more than 180
  characters."), instead of a fixed string — and scrolls the banner into view, matching how
  other steps already surface their own inline errors.
- This is a Step 8 fix only; every other step's save handler (`saveStep6AndContinue`,
  `saveStep7AndContinue`, etc.) still shows a generic message on failure — the same
  `firstValidationError()` helper is available if those are worth wiring up the same way
  later.

**Also removed.** `generateLockCode(dob)`, a function in the same file that set the text of
an element with id `lockCodeDisplay` — an id that does not exist anywhere in the template. The
function was never called from anywhere (no `onclick`, no other function referenced it), so it
was dead code left over from an earlier iteration rather than a live bug. Deleted; nothing
else changes.

**Verified.** `php -l` and a `Blade::compileString()` pass both clean on the edited file, and
`view:clear` + recompile succeeds. Not yet re-driven end to end in a browser against a live
girl card over the 180-character limit — worth a quick manual check next time Step 8 is
touched.

**Follow-up — newline-safe character counting.** The Step 8 save handler now normalises the
letter's line endings before applying its character and line limits. A textarea sent through
`FormData` can arrive as CRLF, which previously added an extra `\r` for every paragraph break
and could reject a girl note that the dashboard correctly showed as 180 characters or fewer.

---

## 29. Public Gift Selection — Centre Present Opened Gift 3

**Symptom.** On the public story's gift-selection page, clicking the centre present opened
Gift 3 instead of Gift 2. The issue affected both boy gift-screen designs and both girl
gift-screen designs.

**Cause.** Each of the four desktop background images places the three presents in a horizontal
row: left, centre and right. The transparent click areas were still laid out as an older
triangle — left, top-right and bottom-centre. As a result, the bottom-centre Gift 3 area sat
over the visible centre present.

**Fix.** Realigned the desktop hotspots in all four templates so the visible presents map
left-to-right to Gifts 1, 2 and 3. The public-story route map was already correct, so no
routing or saved card data changed.

**Verified.** Each template now binds its centre area to `openGiftPage(2)` and its right area
to `openGiftPage(3)`. The injected public-story navigation maps those calls to
`/c/{slug}/gift/2` and `/c/{slug}/gift/3`, respectively.
