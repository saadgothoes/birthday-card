# Dashboard Wizard & Public Story (Dynamic Card Builder)

Work log for the client dashboard card-builder wizard — making Steps 3 through 9 fully
dynamic with real live previews, and the underlying card templates data-driven — and for the
public story those settings drive.

**Scope of this document:** the whole loop. The wizard up to and including the **Ending Page
(Step 8)**, **Music (Step 9)** and **QR Select (Step 10)** in sections 1-16, the **public story** a recipient opens
from the generated link in sections 17-19, the **girl side** of Pages 1-3, Gift 1 and
Gift 2 in sections 20-22, the **rest of the girl side** — Gift 3, its own ending design,
its own QR set and its public story — plus one boy bug fix in sections 23-27, and a
Step 8 error-messaging fix plus a dead-code cleanup in section 28, and the public gift-card
click-target correction in section 29, the music library and story soundtrack in section 30,
and — in section 31 — client self-signup, subscriptions and card limits, the QR
subscription gate, and the New/Recent/Draft card hub, and — in section 32 — the dashboard
redesign, Save as Draft with card labels, resume-at-last-step and the theme-persistence fix.
Both themes are complete, dashboard through to the public story.

**Corresponding commits:**

| Commit          | Covers                                                                          |
| --------------- | ------------------------------------------------------------------------------- |
| `d9fa1c1`       | Steps 3-6 (Welcome, Gift Box, Gift 1, Gift 2) — sections 1-6 below              |
| `c4b818a`       | Gift 3 as Step 7 — section 7                                                    |
| `e44d212`       | Gift 3 interactions + design-safe text limits — sections 8-10                   |
| _(earlier)_     | Ending Page as Step 8, QR Select as Step 9 — sections 11-14                     |
| _(earlier)_     | The public story at `/c/{slug}` — sections 17-19                                |
| _(earlier)_     | The girl side: Pages 1-3, Gift 1 and Gift 2 — sections 20-22                    |
| _(earlier)_     | Girl Gift 3, ending page, QR and public story; boy welcome fix — sections 23-27 |
| _(this change)_ | Step 8 error surfacing fix + dead-code cleanup — section 28                     |
| _(earlier)_     | Public gift-selection click-target correction — section 29                      |
| _(earlier)_     | Music library and story soundtrack — section 30                                 |
| _(earlier)_     | Signup, subscriptions, card limits, QR gate, card hub — section 31              |
| _(this change)_ | Dashboard redesign, Save as Draft, resume + theme fix — section 32              |

---

## 1. Database

Four migrations were added.

### `2026_08_13_000000_add_heading_and_gift_variant_to_birthday_cards_table.php`

| Column                | Type                           | Purpose                                        |
| --------------------- | ------------------------------ | ---------------------------------------------- |
| `heading`             | `string` nullable              | Step 3 — welcome screen heading                |
| `gift_screen_variant` | `unsignedTinyInteger` nullable | Step 4 — which gift-box screen design (1 or 2) |

### `2026_08_13_010000_add_gift1_gift2_data_to_birthday_cards_table.php`

| Column       | Type            | Purpose                                              |
| ------------ | --------------- | ---------------------------------------------------- |
| `gift1_data` | `json` nullable | Step 5 — Gift 1 theme + photos                       |
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

| Method      | Saves                                                               | Advances `current_step` to |
| ----------- | ------------------------------------------------------------------- | -------------------------- |
| `saveStep1` | theme, variant                                                      | 2                          |
| `saveStep2` | lock_code (PIN), profile photo                                      | 3                          |
| `saveStep3` | heading, welcome_message                                            | 4                          |
| `saveStep4` | gift_screen_variant                                                 | 5                          |
| `saveStep5` | gift1_data (theme + up to 3 photos)                                 | 6                          |
| `saveStep6` | gift2_data (theme + up to 4 photos + names + date + text)           | 7                          |
| `saveStep7` | gift3_data (theme + 5 photos + every book page's text)              | 8                          |
| `saveStep8` | ending_data (design + the seven ending-page text slots)             | 9                          |
| `saveStep9` | qr_data (QR design) + the share slug; returns the link and the code | 9                          |

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

`resources/views/client/dashboard.blade.php` — now **10 steps** (`const totalSteps = 10`).
Steps 1-6 landed first; Gift 3 was slotted in as Step 7; the old _Generate & Share_ stub
was then split into the Ending Page and QR Select:

1. Choose Theme
2. Set Lock Code
3. Welcome Screen
4. Gift Box Screen
5. Gift 1
6. Gift 2
7. Gift 3 — see section 7
8. Ending Page — see section 11
9. Music — see section 30
10. QR Select — see section 12

### Step 3 — Welcome Screen

Replaced the old mock preview (fake avatar/name/message card) with the real thing:

- Two fields only: **Heading** and **Message**.
- Defaults auto-populate from the theme + design chosen in Step 1, matching each design's
  own original wording:

    | Theme | Variant | Default heading         |
    | ----- | ------- | ----------------------- |
    | boy   | 1       | Happy Birthday My Love  |
    | boy   | 2       | Happy Birthday King     |
    | girl  | 1       | Happy Birthday My Love  |
    | girl  | 2       | Happy Birthday Princess |

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

    | Field           | Boy   | Girl   |
    | --------------- | ----- | ------ |
    | Photo slots     | 4     | 3      |
    | Name 1 / Name 2 | shown | hidden |
    | Special Date    | shown | hidden |
    | Message         | shown | shown  |
    | Signed          | shown | hidden |

### Resume behaviour

All steps repopulate from the database on page load — theme, variant, PIN, heading,
message, gift-screen choice, gift themes, uploaded photo thumbnails, names, date, message
and signature. A client can close the dashboard mid-build and continue where they left off.

---

## 4. Card Templates Made Dynamic

36 template files under `resources/views/birthday/` were converted from hardcoded content
to query-parameter driven content, using Laravel's `request('key', 'default')` helper.

**Design principle:** every parameter has the template's _own original text_ as its
fallback default. With no query string, each page renders byte-for-byte as it did before —
so the templates still work standalone, and the dashboard preview simply passes params.

### Welcome screen — page 2 (4 files)

`boy-page-2`, `boy-page-2-2`, `girl-page-2`, `girl-page-2-2`

| Param     | Purpose                            |
| --------- | ---------------------------------- |
| `heading` | Main title text                    |
| `message` | Body message (supports multi-line) |

### Gift 1 — boy (8 files)

`boy-page-3-variant-{1,2}-gift-1-page-{1..4}` — polaroid photo board.

| Param                        | Purpose                                 |
| ---------------------------- | --------------------------------------- |
| `photo1`, `photo2`, `photo3` | Replace the decorative SVG placeholders |

### Gift 1 — girl (8 files)

`girl-page-3-variant-{1,2}-gift-1-page-{1..4}` — memory board with calendar.

| Param                        | Purpose              |
| ---------------------------- | -------------------- |
| `photo1`, `photo2`, `photo3` | Polaroid photos      |
| `cal_month`                  | Calendar month label |
| `cal_day`                    | Highlighted day      |
| `message`                    | Handwritten note     |

### Gift 2 — boy (8 files)

`boy-page-3-variant-{1,2}-gift-2-page-{1..4}` — couple memory tiles + calendar + note.

| Param                       | Purpose                                                |
| --------------------------- | ------------------------------------------------------ |
| `photo1`–`photo4`           | The four image tiles (walk / beach / coffee / embrace) |
| `name_first`, `name_second` | Couple names                                           |
| `cal_month`                 | Calendar title                                         |
| `cal_day`                   | Day marked with the heart                              |
| `message`                   | Note text                                              |
| `signed`                    | Signature line                                         |

### Gift 2 — girl (8 files)

`girl-page-3-variant-{1,2}-gift-2-page-{1..4}` — handwritten letter reveal.

| Param                        | Purpose                                             |
| ---------------------------- | --------------------------------------------------- |
| `photo1`, `photo2`, `photo3` | The three polaroids                                 |
| `message`                    | Letter text — newlines split into handwritten lines |

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
pushed _Generate & Share_ to **Step 8** (`const totalSteps = 8`). Boy side only — the
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

| Piece      | Detail                                                                        |
| ---------- | ----------------------------------------------------------------------------- |
| Route      | `POST /client/card/step7` → `client.card.step7`                               |
| Controller | `BirthdayCardController@saveStep7`, advances `current_step` to 8              |
| Uploads    | `birthday-cards/gift3/` — five photos, merged not replaced, as with Gifts 1-2 |
| Model      | `gift3_data` added to `#[Fillable]` and cast to `'array'`                     |

Field names live in **one place**, `GIFT3_TEXT_LIMITS`, which the save endpoint validates
against, the dashboard reads for its `maxlength` attributes, and the templates read off the
query string. `gift3TextKeys()` exposes the key order.

### 7.3 Dashboard — a book-page sub-wizard

Thirty-odd inputs on one panel would have buried the step, so Step 7 walks the book **one
page at a time**:

- 4 theme thumbnails — real iframes of `/boy/page/3/{giftScreenVariant}/gift/3/{1-4}`.
- A progress strip of 10 dots; one `.book-page-panel` visible at a time, with
  _Previous page_ / _Next page_.
- **Continue** only appears on the last book page, so the whole book gets walked through.
- A live preview under the fields that **jumps to the page being edited** — the templates
  take a `preview_page=N` parameter that opens the book straight to that page.
- All five photos are required before Continue will save.

Gift 3 renders in a **portrait** iframe (460×723, `.gift3-thumb` / `.gift3-preview`) rather
than the 900×562 the other steps use: the book is sized off viewport _height_, so in a
landscape frame it shrank to a sliver in the middle.

### 7.4 Templates

All 8 boy files (`boy-page-3-variant-{1,2}-gift-3-page-{1..4}`) — the four "themes" differ
only in their colour tokens, so their markup and script are identical and every change is
applied to all 8. The closed cover is deliberately **not** editable.

---

## 8. Gift 3 — Interactions and Design-Safe Text

### 8.1 What changed on the page

| Book page  | Change                                                                                                                                                                           |
| ---------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Cover      | The inside face was a blank slab for the whole 1.4s swing; it now carries a small "Our Story" plate.                                                                             |
| 5 — Letter | Paper sheet enlarged and anchored top-and-bottom; paragraphs get a small margin instead of a full blank line; font auto-steps down (`.sm` / `.xs`) as the letter gets longer.    |
| 6 — Dates  | Each row takes an ISO date from a **date picker** and prints it long-form (`12 Mar 2020`). Free text entered before this change still renders as typed.                          |
| 7 — Dreams | Now **3 or 4** items (`dream_count`), not a fixed 5. Defaults: First Coffee ✓, First Selfie ✓, First Trip ✓, Grow Old Together.                                                  |
| 8 — Quote  | Written out a character at a time, with a blinking caret and a longer pause on punctuation. Height is reserved up front (after `document.fonts.ready`) so the page doesn't jump. |
| 9 — Ribbon | A **Click to Open** button beside the hint; the ribbon itself is clickable too. Page text is kept to the clear column left of the ribbon.                                        |
| 10 — Final | **Close the Book** added beside Replay Story.                                                                                                                                    |

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

| Constant                                | Fields                                                                                                                                                                                  |
| --------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `WELCOME_LIMITS`                        | heading 40, message 160 (+ 4-line cap)                                                                                                                                                  |
| `GIFT2_LIMITS`                          | name 14, signed 30, note **180 boy / 300 girl** — the ceiling follows the theme, because the boy design uses a small fixed note panel and the girl one a taller scrollable letter sheet |
| `GIFT3_TEXT_LIMITS`                     | 30 slots, below                                                                                                                                                                         |
| `GIFT3_LETTER_MAX_LINES`                | 10 — characters alone don't bound the letter's height                                                                                                                                   |
| `GIFT3_MIN_DREAMS` / `GIFT3_MAX_DREAMS` | 3 / 4                                                                                                                                                                                   |
| `ENDING_TEXT_LIMITS`                    | 7 slots, see section 11.4                                                                                                                                                               |
| `ENDING_LETTER_MAX_LINES`               | 14 — as with Gift 3's letter, characters alone don't bound the height                                                                                                                   |

Gift 3, by book page:

| Page | Field(s)                      | Limit          | Why                                       |
| ---- | ----------------------------- | -------------- | ----------------------------------------- |
| 1    | `eyebrow`                     | 28             | uppercase display face with 4px tracking  |
| 1    | `from_name`, `to_name`        | 18             | script face at up to 42px                 |
| 2    | `caption`                     | 45             | italic line under a full-width photo      |
| 3    | `memory_text`                 | 70             | narrow half-width column beside the photo |
| 4    | `polaroid_label`              | 32             | page label                                |
| 4    | `note1`-`note3`               | 18             | handwritten strip under a polaroid        |
| 5    | `letter_label`                | 32             | page label                                |
| 5    | `letter`                      | 280 + 10 lines | plus the auto font step-down              |
| 5    | `envelope_hint`               | 28             |                                           |
| 6    | `dates_label`                 | 32             |                                           |
| 6    | `date1_name`-`date4_name`     | 22             | shares its row with the date itself       |
| 7    | `dreams_label`                | 32             |                                           |
| 7    | `dream1`-`dream4`             | 24             | row width minus the tickbox               |
| 8    | `quote`                       | 120            |                                           |
| 9    | `secret_label`                | 32             |                                           |
| 9    | `secret_button`               | 20             | pill button                               |
| 9    | `secret_message`              | 48             |                                           |
| 10   | `final_line1`, `final_line2`  | 42             |                                           |
| 10   | `replay_label`, `close_label` | 20             | pill buttons                              |

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

| Page      | Overflow                                                             |
| --------- | -------------------------------------------------------------------- |
| 1 — Title | `.heart-deco` pushed ~6-13px past the page at the smallest viewports |
| 6 — Dates | ~45px when all four titles are 22 chars _and_ all four dates are set |
| 8 — Quote | ~86px at the full 120 characters                                     |

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

| Column        | Type            | Purpose                                           |
| ------------- | --------------- | ------------------------------------------------- |
| `ending_data` | `json` nullable | Step 8 — ending design + all seven text slots     |
| `qr_data`     | `json` nullable | Step 9 — chosen QR design + when it was generated |

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

| Piece      | Detail                                                           |
| ---------- | ---------------------------------------------------------------- |
| Route      | `POST /client/card/step8` → `client.card.step8`                  |
| Controller | `BirthdayCardController@saveStep8`, advances `current_step` to 9 |
| Limits     | `ENDING_TEXT_LIMITS` (7 slots) + `ENDING_LETTER_MAX_LINES` (14)  |

`endingTextKeys()` exposes the key order, so the dashboard, the validator and the templates
all read one list — the same arrangement Gift 3 uses.

### 11.4 Templates

All four boy templates (`boy-page-4`, `-4-2`, `-4-3`, `-4-4`) are now query-parameter
driven, each parameter defaulting to the template's own original text, so the pages still
render byte-for-byte unchanged with no query string.

| Param            | Slot                 | Limit          |
| ---------------- | -------------------- | -------------- |
| `title`          | envelope heading     | 28             |
| `subtitle`       | line under it        | 48             |
| `tap_label`      | uppercase tap hint   | 20             |
| `letter_heading` | heading on the paper | 32             |
| `letter`         | the letter itself    | 500 + 14 lines |
| `signoff`        | signature line       | 28             |
| `end_label`      | closing stamp        | 20             |

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

Step 9 replaces the old _Generate & Share_ panel, which was a client-side stub that
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

| #   | Boy                                         | Girl (not wired up yet) |
| --- | ------------------------------------------- | ----------------------- |
| 1   | Midnight Navy — classic squares, ivory card | Dusty Pink              |
| 2   | Steel Glow — rounded modules, blue gradient | Blush Petal             |
| 3   | Graphite Ice — dark card, ice blue dots     | Rose Gold Noir          |
| 4   | Blueprint — dashed frame, plain squares     | Lavender Line           |

### 12.3 The share link

The card's `slug` is settled **when the dashboard first renders**, not at publish time
(`ensureSlug()`, called from `ClientAuthController@dashboard`). A QR encodes a URL, so the
four previews can only be honest if the address is already fixed — otherwise the previews
would encode one URL and the generated code another.

The slug is `{to-name}-{6 random chars}`, and `shareUrl()` builds `/{PUBLIC_CARD_PATH}/{slug}`
— `/c/{slug}`. **The public story flow itself is Task 3**; only the address is fixed here,
so a code generated today still points at the right place once that flow lands.

### 12.4 Backend

| Piece      | Detail                                                                     |
| ---------- | -------------------------------------------------------------------------- |
| Route      | `POST /client/card/step9` → `client.card.step9`                            |
| Controller | `BirthdayCardController@saveStep9`                                         |
| Saves      | `qr_data` = `{ theme, generated_at }`, plus the slug if it was still unset |
| Returns    | `slug`, `share_url`, `qr_svg` (720px)                                      |

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

_(As of section 17 the public story is built — this list is superseded by section 19.)_

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

_(Resolved since the last revision: `vendor/endroid/` is no longer orphaned — see 12.1;
`ending_variant` / `ending_message` are dropped — see 11.1; `boy-page-4-3.blade.php` is no
longer corrupted — see 13.)_

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

| Route                        | Name            | Page                     |
| ---------------------------- | --------------- | ------------------------ |
| `GET /c/{slug}`              | `story.lock`    | Page 1 — the lock screen |
| `POST /c/{slug}/unlock`      | `story.unlock`  | code check               |
| `GET /c/{slug}/welcome`      | `story.welcome` | Page 2                   |
| `GET /c/{slug}/gifts`        | `story.gifts`   | Page 3 — gift selection  |
| `GET /c/{slug}/gift/{1,2,3}` | `story.gift`    | the three gifts          |
| `GET /c/{slug}/ending`       | `story.ending`  | the ending page          |

An unknown slug is a 404. A girl card is a 404 too, for now — the dashboard does not
configure the girl side, so there is nothing honest to render. Adding `'girl'` to
`PublicStoryController::LIVE_THEMES` is what turns it on; the whole mapping below is already
theme-agnostic.

### 17.2 There is no second copy of the story

`PublicStoryController` looks the card up by slug, turns its stored JSON into the **same
query parameters the dashboard's live preview passes**, merges them into the request, and
renders the **same template**. The templates already read their content with
`request('key', default)`, so nothing new had to be taught to them.

| Page    | Template               | Comes from                                                                          |
| ------- | ---------------------- | ----------------------------------------------------------------------------------- |
| Lock    | `boy-page-1{-2}`       | `variant`, `profile_image_path`, `lock_code`                                        |
| Welcome | `boy-page-2{-2}`       | `variant`, `heading`, `welcome_message`                                             |
| Gifts   | `boy-page-3{-2}`       | `gift_screen_variant`                                                               |
| Gift 1  | `…gift-1-page-{n}`     | `gift1_data` — design + 3 photos                                                    |
| Gift 2  | `…gift-2-page-{n}`     | `gift2_data` — design, 4 photos, names, `cal_day`, note, signature                  |
| Gift 3  | `…gift-3-page-{n}`     | `gift3_data` — design, 5 photos, all 30 text slots, dates, tick states, dream count |
| Ending  | `boy-page-4{-2,-3,-4}` | `ending_data` — design + 7 text slots                                               |

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

| Page       | What the chrome does                                                                                                                                                                           |
| ---------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Lock       | Turns the decorative numpad into a real 4-digit entry, drops the client's photo into the frame, adds the error line. Matches all three designs' class names (`.boy2-*`, `.girl-*`, `.girl2-*`) |
| Welcome    | Points the design's own **NEXT** button at the gift screen                                                                                                                                     |
| Gifts      | Redefines `openGiftPage(n)` — which pointed at a URL that was never built — keeping the design's loading flourish                                                                              |
| Gift 1 / 2 | A floating **Next →** back to the gift screen                                                                                                                                                  |
| Gift 3     | A floating **← Gifts** and **One Last Thing →**                                                                                                                                                |
| Ending     | A floating **← Back to the gifts**                                                                                                                                                             |

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

| Page            | State before                                                          | Now                                                                                         |
| --------------- | --------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| 1 — Lock        | Design 2 took a `photo`; design 1 drew an illustration with no way in | Design 1 takes `photo` too, with the illustration kept as the fallback when there isn't one |
| 2 — Welcome     | Already took `heading` and `message`                                  | Unchanged                                                                                   |
| 3 — Gift screen | Both designs already offered and saved in Step 4                      | Unchanged                                                                                   |

So the only real gap was the lock screen's photo, which meant a girl client uploaded a
picture in Step 2 and then couldn't see it in the preview. `girl-page-1` now renders the
upload inside its oval frame and falls back to the original artwork when no photo is set —
the template still renders byte-for-byte as before with no parameters.

### 20.2 Gift 1 — the girl design's own fields

The girl Gift 1 templates were **already** parameter-driven (`photo1-3`, `cal_month`,
`cal_day`, `message`). What was wrong was the dashboard: Step 5 offered three photo slots and
nothing else, because that is all the _boy_ design has. A girl client could pick one of the
four girl themes and then had no way to set the calendar it prints or the note beside it.

Step 5 is now theme-aware, the same way Step 6 already was:

| Field            | Boy    | Girl  |
| ---------------- | ------ | ----- |
| Photo slots      | 3      | 3     |
| Special Date     | hidden | shown |
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

| Beat             | Fields                           | Limit |
| ---------------- | -------------------------------- | ----- |
| 1 — The Gift Box | Line above the box (`box_title`) | 34    |
|                  | Prompt under it (`box_hint`)     | 20    |
| 2 — Photo 1      | photo + caption (`cap1`)         | 18    |
| 3 — Photo 2      | photo + caption (`cap2`)         | 18    |
| 4 — Photo 3      | photo + caption (`cap3`)         | 18    |
| 5 — The Letter   | `message`                        | 300   |

- A progress strip of five dots, **Previous / Next** between beats, and **Continue** held
  back until the last one so the whole scene gets walked through. (This is the missing Next
  button — before, the girl side had no way through the step at all.)
- The two sides now have **separate editors**: `#gift2BoySide` is the original single form,
  `#gift2GirlSide` the five beats, and only one is ever shown.
- Each beat carries its own photo slot, driven by the _same_ file input as the boy form's, so
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

## 22. Girl Side — Still To Do _(superseded by section 26)_

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

| Beat | Card            | Fields                                                                             |
| ---- | --------------- | ---------------------------------------------------------------------------------- |
| 1    | The Cover       | `cover_title`, `cover_sub`, `cover_tap`, `gallery_title`                           |
| 2    | Photo 1         | photo + `p1_date`, `p1_place`, `p1_caption`                                        |
| 3    | Video Clip 1    | cover still + **clip upload** + `v1_duration`, `v1_date`, `v1_place`, `v1_caption` |
| 4    | The Chat        | `chat_name`, `chat1`, `chat2`, `chat3`, `chat_date`, `chat_caption`                |
| 5    | Photo 2         | photo + `p2_date`, `p2_place`, `p2_caption`                                        |
| 6    | The Pinned Note | `note_date`, `note_text` (3-line cap)                                              |
| 7    | Video Clip 2    | cover still + **clip upload** + `v2_*`                                             |
| 8    | Photo 3         | photo + `p3_date`, `p3_place`, `p3_caption`                                        |
| 9    | A Love Letter   | `letter` (12-line cap), `signoff`                                                  |

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

| Design      | Parameter              | Effect                                                               |
| ----------- | ---------------------- | -------------------------------------------------------------------- |
| Girl Gift 2 | `preview_stage=photos` | Skips the box, shows all three polaroids at once with their captions |
|             | `preview_stage=letter` | …and opens the envelope with the letter already written              |
| Girl Gift 3 | `preview_card=N`       | Opens the roll, reveals every card, scrolls to card N                |

The dashboard sets them from whichever beat is on screen, so the preview moves with the
client. Both fast-forwards reveal _all_ the cards or photos at once rather than staging them
in one at a time, because the staging is what hid the thing being edited.

### 23.3 A Girl ending page — "The Bloom"

The girl ending templates were a recolour of the boy's envelope. They have been replaced
with a design of their own: a closed flower sits on a soft field, and tapping it unfurls ten
petals to reveal a **round keepsake card** that writes the note out by hand, with petals
drifting past throughout.

Four colourways, same markup, colour tokens only — the convention the other four-theme sets
use:

| #   | Name           |                                |
| --- | -------------- | ------------------------------ |
| 1   | Blush Rose     | blush petals on cream          |
| 2   | Lilac Dusk     | lavender petals on soft violet |
| 3   | Rose Gold Noir | dark field, rose gold bloom    |
| 4   | Plum Midnight  | midnight field, plum bloom     |

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

| #   | Name           | Modules              | Frame         |
| --- | -------------- | -------------------- | ------------- |
| 1   | Blush Petal    | rounded              | double, blush |
| 2   | Lilac Confetti | dots                 | none          |
| 3   | Rose Gold Noir | dots on a dark card  | double        |
| 4   | Plum Midnight  | rounded on deep plum | solid         |

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

| Constant                                                    | Notable slots                                                                          |
| ----------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| `GIFT3_GIRL_TEXT_LIMITS`                                    | 30 slots; captions **34**, chat bubbles 44, note 90 (+3 lines), letter 420 (+12 lines) |
| `GIFT3_GIRL_NOTE_MAX_LINES` / `GIFT3_GIRL_LETTER_MAX_LINES` | 3 / 12                                                                                 |
| `GIFT3_GIRL_VIDEO_MAX_KB`                                   | 20480                                                                                  |
| `ENDING_GIRL_TEXT_LIMITS`                                   | title 24, subtitle 40, tap 18, heading 26, **note 180**, signoff 22, closing 18        |
| `ENDING_GIRL_LETTER_MAX_LINES`                              | 8                                                                                      |

The girl ending's note is capped well below the boy's 500 because a circle holds far less
than a rectangular sheet, and the keepsake card does not scroll.

### Bugs the sweep found

**The keepsake card's padding was a percentage.** Percentage padding resolves against the
_containing block's_ width, not the element's — so at 1440px wide, `15%` was 210px a side on
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
- **Design-safe limits, 64 cases** — every new girl slot filled to its exact ceiling _at the
  same time_, across both gift-screen variants, all four designs each, at 360×640, 390×844,
  the preview frame and desktop. Nothing overflows, nothing is clipped, no page scrolls
  sideways.
- **QR scannability, all 8 themes** — each rendered SVG rasterised and sampled at every
  module centre against the encoder's own matrix: zero mismatched modules and ≥45% contrast
  on all eight. Two girl finder rings were darkened after the first pass measured 41% and
  48%.
- **Girl public story, 26 assertions** — the link opens the girl lock screen, the code is not
  in the source, a wrong code is rejected, the right one opens the welcome page, NEXT reaches
  the gifts, each gift opens with its configured content, the roll carries every configured
  card _and the uploaded clips_, and the ending is **girl design 3's bloom**, which opens into
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

## 30. Music Library and Story Soundtrack

The QR step is now Step 10. A new **Music** step (Step 9) sits immediately after the Ending Page and before QR generation.

### Super Admin music library

Super Admins can open **Music Library** from the admin sidebar and manage the shared catalogue:

- Upload MP3, MP4, M4A, WAV or OGG files up to 100 MB. Uploads are sent in 1 MB chunks so PHP's normal request limit is never exceeded.
- Add a title, optional artist, and an `English` or `Hindi` category.
- Preview, hide/show, and delete tracks.
- Only active tracks appear in the client dashboard.

The catalogue is stored in `music_tracks`; uploaded files live under `storage/app/public/music-library/`. Default English/Hindi songs are uploaded and maintained by Super Admin rather than hardcoded into the application, so the library can change without a deployment.

### How audio is served

Audio goes through the app, not the public disk: `GET /music/{track}` → `MusicStreamController`, and `MusicTrack::url` returns that route.

This is not cosmetic. The story plays a chosen minute out of a track, so the player has to **seek**, and seeking needs the server to answer a `Range` request with the bytes around that offset. Served as a plain static file, that was left to whatever sits in front of the app — and the built-in server answers a Range request with `200` and the whole 12 MB file. The browser then cannot seek at all: `play()` stalls with no sound, no error and nothing in the console. A misconfigured nginx or a CDN in the way does the same thing.

`response()->file()` returns a Symfony `BinaryFileResponse`, which implements Range itself, so every deployment now answers `206 Partial Content` with a `Content-Range` regardless of what is serving the app:

```
$ curl -I -H 'Range: bytes=2100000-2200000' /music/1
HTTP/1.1 206 Partial Content
Content-Type: audio/mpeg
Content-Range: bytes 2100000-2200000/12709408
```

- Inactive tracks are still streamed. Hiding a song in the library stops it being offered to new cards; the cards already using it must keep playing.
- The route is public — the recipient of a card is not logged in to anything.
- URLs are relative (`/music/1`), so they are always same-scheme as the page asking, which an absolute URL is not behind a proxy that doesn't forward the scheme.
- The file behind a track never changes (a new upload is a new row with a new UUID), so it is cached for 30 days.

### Client Music step

Clients can choose one active library track only; custom client uploads are disabled. The selection is stored in `birthday_cards.music_data`.

#### Picking the part

A five-minute track is rarely what a card wants playing behind it, so choosing a song opens a **clip picker** underneath the grid. It works the way picking music for a social story does: the length is fixed at two minutes and the client chooses *which* two minutes, sliding a window along the track until it sits over the right part.

- **Exactly two minutes** — `BirthdayCardController::MUSIC_CLIP_SECONDS` (120 s), which is both the minimum and the maximum. There is no control for length at all, only for position. A song no longer than the clip has nothing to pick out of it and plays whole.
- **One slider, and its thumb is the selection.** The thumb is widened to the share of the track the clip covers (`--clip-window`, set from the duration), so the thing being dragged *is* the window. This falls out of how a range input already works — its thumb travels the rail minus its own width, which is exactly the distance the window has to move to sweep the song — so drag, keyboard and touch all come for free.
- **You hear what you pick.** Dragging scrubs: the part under the handle plays from the moment you move there. Letting go starts it if nothing was playing, so choosing a part and hearing it are one gesture. The preview loops the window rather than running on into the rest of the song, with a progress bar and a volume slider under it — what the client hears is what the recipient gets.
- The window is stored as `trim_start` / `trim_end` (seconds) in `music_data`; a song too short to clip stores `null` for both, which plays the file as it is.
- The handles are placed from the song's duration, which only arrives with the file's metadata, so the picker opens in a reading state and fills itself in. If a duration can't be read, the whole track plays.
- Picking a different song resets the window to the song's opening stretch; re-opening Step 9, or re-picking the song already chosen, restores it. A saved window that no longer fits the track falls back to the opening.
- The length is capped **twice** server-side and never trusted from the browser: `saveStep9` clamps `trim_end` to `trim_start + 120`, and `PublicStoryController::musicClip()` clamps again on the way out. Cards saved under an earlier, shorter rule keep the window they have until the client picks again — the clamp is a ceiling, not a rewrite.

The QR flow is now:

```text
Ending Page (8) -> Music (9) -> QR Select (10)
```

### Public story playback

After the passcode is accepted the chosen part starts on the Welcome page and plays — without stopping, restarting or skipping — through the welcome, gifts, book and ending pages, repeating for as long as the recipient stays in the story — the clip restarts itself the moment it ends, so the story is never silent.

#### The story shell

Every card design is a standalone HTML document with its own `<head>`, styles and scripts. There are seventy-odd of them and no shared layout, which is why the story's navigation is injected after rendering rather than written into them. That also left nowhere to hang an `<audio>` element that survives a click on Next: a full navigation destroys the page and everything in it, so a player living inside a card can only be rebuilt on the next page and nudged back to roughly where it was.

`resources/views/story/shell.blade.php` is the common layout the project never had. The shell holds the player and its controls; the story runs in a frame inside it. Moving through the story navigates the frame, and the shell — with the music in it — is never reloaded at all. **The track is not resumed between pages because it never stopped.**

The card designs needed no changes for this: they are already standalone documents that the dashboard previews in a frame, so they render in the shell exactly as they do on their own.

- **Which half you get.** `PublicStoryController::insideShell()` reads `Sec-Fetch-Dest: iframe`, which browsers send for frame navigations — so shared links stay clean (`/c/abc/gifts`, no flag). The shell also puts `frame=1` on the src it asks for, both for browsers too old to send the header and so a request is never ambiguous. A shell that still finds itself nested replaces itself with the page, which costs one extra request on those older browsers and nothing on the rest.
- **The address bar.** The shell never navigates, so on each frame load it reads the frame's path and `history.replaceState`s it — a refresh or a shared link lands where the recipient actually is.
- **The badge.** A small pill in the bottom-left corner. It lives outside the frame, so it sits above every card design without knowing anything about them, and it persists across pages the way the music does. It is not interactive — see "The badge, not a player" below.
- **When it starts.** By itself, and only once the story is unlocked: the lock screen is not part of the story, so music starts on the unlock signal or the first frame load past the lock — and landing on the lock screen is what clears a previous run's saved position.
- **State.** Track, window and position go to `localStorage` under `story-music:{slug}`, written about twice a second and again on `pagehide`. The shell only loads once per visit, so this is for a reload of the shell itself — not for moving between pages, which needs no state at all. The track URL and the clip window are compared before restoring, so re-picking a card's music doesn't drop the new clip in at the old position.
- **Autoplay.** A reload spends the page's autoplay permission. If `play()` is refused, the next touch — in the shell or inside the frame — starts the music; the shell arms listeners in both documents, since clicks inside a frame never reach its parent.

#### The standalone fallback

`StoryChrome::music()` still injects a self-contained player into each page, and it stands down (`window.top !== window.self`) whenever the shell is above it, so the music is never doubled. It matters for a card design opened on its own, outside the shell — which has to keep working, because the dashboard previews them exactly that way. That player carries the position across pages in `sessionStorage` and restores it on the next one; the notes below are what make that restoring hold up.

- The element is **built in script, not written into the markup**. A parsed `<audio autoplay>` starts at the top before any handler can seek it, and when the file is already cached its `loadedmetadata` fires before the handler is even attached — the seek is missed and the track genuinely restarts. The element is created silent and faded in only once the seek has landed.
- The clip **loops**, so a story that outlasts the song never falls silent. Reaching `trim_end` sends the playhead back to `trim_start` instead of letting the rest of the track run, so the clip replays for as long as the recipient stays; an untrimmed song simply loops whole.
- The saved position is cleared when the passcode is accepted, so each fresh run of the story begins at the top of the clip.

### 30.1 Music playback fixes and player polish

Three problems were reported: the dashboard's clip preview made no sound, the story's music did not play once a recipient was past the lock screen, and the corner player needed a better face.

#### The root cause of both silences

Both were the same bug, and neither was in the JavaScript. Audio was served as a static file from the public disk, and **the server answered `Range` requests with `200` and the entire 12 MB body** — no `Accept-Ranges`, no `206`, no `Content-Range`.

Every part of this feature seeks: the picker previews from partway in, and the story starts partway into the track. Without Range, the browser cannot seek — `play()` stalls with no sound, no error and nothing in the console, which is exactly what "the music is not audible" looks like. Serving audio through `MusicStreamController` fixed both at once; see "How audio is served" above.

#### Dashboard preview

- `preload="auto"`, so the bytes around the chosen part are already there when Play is pressed.
- `muted` and `volume` are set explicitly on every play rather than inherited from whatever state the element was left in, and a volume slider sits under the picker.
- A rejected `play()` now says so in the picker instead of being swallowed by an empty `catch`; buffering says so; a file that will not load disables the button with a message. A silent preview used to look identical to a working one.
- Moving the slider seeks immediately and loops the preview inside the window; letting go starts it, so choosing a part and hearing it are one gesture.

#### Starting on the unlock gesture

`StoryChrome::lock()` posts `birthday-story-unlocked` to the shell the moment the code is accepted, and the shell starts the music on it rather than waiting for the welcome page to finish loading a second and a half later. That click is the recipient's user gesture; using it directly is the difference between playing and being refused on browsers that expire activation across a navigation. By the time the frame moves on, the song is already going — which is the point of the shell. The message is origin-checked on arrival.

The frame-load path still starts the music too, and `startMusic()` is idempotent, so a recipient who reloads mid-story or deep-links past the lock is covered by whichever fires first. That path also now handles a frame that finished loading before the shell's own script ran — a cached page, or simply a fast one — by checking `readyState` as well as listening for `load`.

#### The badge, not a player

The music is the card's, not the recipient's. It starts itself once the story is unlocked and simply plays, so what sits in the corner is a **sign, not a control**: no play/pause, no volume, no mute, no off switch. An earlier revision carried all of those; they were removed deliberately.

- **Nothing to press.** The badge carries `pointer-events: none` throughout, so it can never take a tap meant for the story underneath it. That is what keeps it from disturbing the page it sits over.
- **Small, and smaller still.** It arrives at the bottom-left with the song's name and artist, then tucks itself away after about five seconds to a round badge of moving bars, giving the corner back.
- **Alive only when the sound is.** The equalizer animates off the element's real `play`/`pause` state, so it never claims to be playing while silent.
- **Themed** — ice-blue on boy cards, rose on girl cards, so it reads as part of the story rather than a browser control parked on top of it.
- **Responsive** — narrower pill and tighter margins on phones; hidden in print.

Removing the controls removes the only way a recipient could have rescued a blocked autoplay by hand, so the two automatic paths matter more than before: the unlock-gesture start above, and a listener armed on **any** touch, anywhere in either document, that starts the music without having to be aimed at. A tab returning to the foreground resumes too.

Only the playhead position is kept in `localStorage`, for a reload of the shell itself. Moving between pages needs none of it: the element is never torn down.

`StoryChrome::music()` — the standalone fallback for a card design opened outside the shell — keeps its clip looping and cross-page position behaviour unchanged, and still stands down whenever the shell is above it.

---

## 31. Self-Signup, Subscriptions, Card Limits and the Card Hub

This change turns the product from an admin-provisioned tool into one people sign
up for themselves, puts a subscription gate in front of QR generation, and replaces
the single implicit draft with a proper multi-card workspace.

**Payment integration is deliberately not included** — see "Still pending" at the end
of this section.

### 31.1 Signup flow changes

Accounts are no longer created by the Super Admin.

- The landing page nav now carries **Login and Sign Up** side by side
  (`landing/_nav.blade.php`, with `.l-nav__actions` / `.l-nav__login` in `landing.css`).
- `client/register.blade.php` is the new signup form — name, email, phone, city, age
  and a self-chosen password. It reuses the login page's styling so the two match.
- `ClientAuthController@registerPage` / `@register` create the account, sign the person
  in, and drop them on the card hub. New accounts are `status = active`,
  `subscription_status = none`, `password_changed = true` — a self-chosen password
  means there is no generated credential to email or nag about.
- The client login page links across to signup, and vice versa.

**Removed:** `ClientController@create` / `@store`, the `admin.clients.create` and
`admin.clients.store` routes, `admin/clients/create.blade.php`, and the "Add Client"
entries in every admin sidebar and the dashboard quick-actions. The welcome-mail path
that sent generated credentials is no longer reachable from the client flow.

**Kept unchanged:** Disable/Enable. `ClientController@toggleStatus` and its route,
button and badge behave exactly as before.

### 31.2 Super Admin changes

`admin/clients/index.blade.php` now reports, per client: name/city/age, contact,
**plan**, **subscription status** (with a "n requests waiting" flag), **cards used /
card limit** with the remainder, **device count**, and account status. Row counts come
from `withCount('birthdayCards')` plus two grouped queries, so the list does not fan
out into a query per row.

`admin/clients/show.blade.php` is new — the full picture for one client:

| Panel                 | Shows                                                              |
| --------------------- | ------------------------------------------------------------------ |
| Headline tiles        | plan, subscription state, cards created, card limit, remaining, logins |
| Account Details       | name, email, phone, city, age, status, signup date, plan fee       |
| Logged-in Devices     | browser + platform, IP, last-active, one row per live session      |
| Subscription Requests | full history, with approve/reject inline and a Revoke control      |
| Cards                 | every card, its theme, wizard progress, draft/completed, last edit |

**Devices/browsers** are read from the database session store (`SESSION_DRIVER=database`,
already the project default) via `User::activeSessions()`. `User::describeDevice()`
turns a user-agent into "Chrome on Windows" — the Edge/Opera/Chrome ordering matters,
since those UAs all contain earlier needles. No UA-parsing package was added; unknown
agents degrade to "Unknown device" rather than dumping a raw string at the admin. This
is the most login/device detail the system can honestly provide: it is live sessions,
not a historical login audit.

### 31.3 Subscription and card limits

`app/Support/SubscriptionPlans.php` is the single catalogue:

| Amount   | Cards |
| -------- | ----- |
| Rs 199   | 1     |
| Rs 399   | 3     |
| Rs 599   | 6     |

`FREE_CARD_LIMIT = 1` is what an account gets before any plan is approved — enough to
build one card all the way to the QR step, which is where the gate actually is.

Users columns added (`2026_08_21_100000`): `subscription_status` (`none` / `pending` /
`active`), `plan_amount`, `card_limit`, `subscription_activated_at`.

The client sees **current plan, allowed cards, created cards and remaining cards** in two
places: the plan bar at the top of the card hub, and the plan strip in the wizard sidebar.

Enforcement is server-side, not just in the UI — `User::canCreateCard()` is checked in
`CardManagerController@store` (flash + refusal) and in
`BirthdayCardController::createCardForCurrentUser()` (403). The New Card tile also
renders disabled when nothing is left, but that is presentation, not the control.

### 31.4 Subscription approval flow

No payment is taken. The request itself is the whole flow.

```text
client picks a plan  →  subscription_requests row (pending), user → pending
        ↓
Super Admin approves →  user → active, plan_amount + card_limit set
        ↓
client can generate QR
```

- `subscription_requests` (`2026_08_21_100001`): user, plan amount, card limit, status,
  reviewer, reviewed-at, note.
- Client side: `CardManagerController@requestSubscription`, reachable from the card hub
  modal and from the gate inside the QR step. One pending request at a time.
- Admin side: `Admin\SubscriptionController` and `admin/subscriptions/index.blade.php` —
  a pending queue and a reviewed history, plus approve/reject from the client detail page.
- Approving sets `subscription_status = active`, `plan_amount`, `card_limit` and the
  activation date. Rejecting only clears a *pending* flag; it never revokes a plan that
  was already approved. `revoke` is the separate, deliberate action for that.

### 31.5 QR restriction

`BirthdayCardController@saveStep10` returns **403** with
`{"reason": "subscription_required"}` when the account has no active plan. The check is
first in the method, before validation, so no work happens on a gated call.

In the dashboard, Step 10 shows a subscription gate above the Generate button: the plan
choices and a "Send Request to Admin" button, or — once a request exists — a pending
notice with the plan and date. The Generate button renders `disabled`, the client-side
`HAS_SUBSCRIPTION` guard scrolls to the gate rather than firing a doomed request, and the
403's own message is surfaced instead of the generic "please try again".

Building and editing a card is **not** restricted. Only turning it into a shareable
link and code is.

### 31.6 New / Recent / Draft

The old model was "the client's latest unpublished card" — implicit, single, and the
reason a new card came up carrying the previous card's data.

**`client/cards.blade.php`** is the new hub, and where login now lands:

- **New Card** — leads the Recent grid, creates a genuinely empty row, nothing copied.
- **Recent** — the six most recently touched cards, newest edit first.
- **Drafts** — everything not yet finished.
- **Completed** — cards whose QR has been generated.

Each tile shows its own progress (`Step n of 10`), a draft/completed pill, and Edit and
Delete. Delete also removes that card's uploads, so the plan slot comes back clean.

**Card identity** is the substantive fix. `currentDraft()` now resolves a card
explicitly, in order: the `X-Card-Id` header → a `card_id` field → the session's
`active_card_id` → only then the newest draft. Every lookup is scoped to
`Auth::id()`, so one client cannot reach another's card by guessing an id.

The dashboard opens on `?card={id}` (no id → redirect to the hub) and installs a small
`window.fetch` wrapper that attaches `X-Card-Id` to every `/client/card*` save. All
eleven step saves already went through `fetch`, so one wrapper covers them without
touching each call site. Two tabs editing two different cards stay independent.

New columns (`2026_08_21_100002`): `title`, `last_opened_at`, and a `(user_id,
is_published)` index. Generating a QR now sets `is_published = true`, which is what
moves a card out of Drafts into Completed.

### 31.7 Verified

- Signup → build → QR blocked (403) → request Rs 399 → admin approves → plan active,
  limit 3, remaining 2 → QR generates and returns a share URL, card marked published.
- Card limit refuses a second card at 1/1.
- Writing to card A while card B is the newer draft leaves B untouched, and vice versa.
- A second client aiming `X-Card-Id` at another client's card cannot reach it.
- All 17 pages render (landing, login, signup, forgot-password, admin login, card hub,
  profile, settings, admin dashboard/clients/client-detail/subscriptions/payments/music/
  bg-owner, wizard, public story). The rendered wizard JavaScript parses clean.
- The public story at `/c/{slug}` is unaffected — it never filtered on `is_published`.

### 31.8 Still pending

- **Payment integration is not implemented.** Plan amounts are what a client is asking
  to be put on, not something they have paid. Approval is a manual Super Admin action,
  and `subscription_fee` is set from the approved plan for reporting only. A real
  gateway, receipts, renewals and expiry all remain to be built — there is currently no
  expiry at all, so an approved subscription stays active until revoked.
- Login/device reporting is live sessions only; there is no historical login audit.

---

## 32. Dashboard Redesign, Save as Draft, and Resume-Where-You-Left-Off

Section 31 introduced multiple cards and the card hub. This change makes that hub a
real dashboard, gives drafts a name the client chooses, and — the substantive fix —
makes reopening a draft actually resume it.

### 32.1 Changes implemented

| Area              | Change                                                                       |
| ----------------- | ---------------------------------------------------------------------------- |
| Dashboard UI      | Rebuilt with a sidebar, stat row, subscription panel and activity feed        |
| Sidebar           | **Main Dashboard** added as the first item, in the hub and in the wizard      |
| Save as Draft     | New button + label modal, in the topbar and immediately before QR generation  |
| Draft editing     | Reopens at the last saved step, not Step 1                                    |
| Theme persistence | Fixed — the saved design was being discarded on reopen                        |
| Multiple cards    | Verified independent across theme, step data and draft labels                 |

### 32.2 Dashboard updates

`client/cards.blade.php` was rebuilt. It is now a fixed-sidebar layout rather than a
single centred column:

- **Sidebar** — **Main Dashboard** first, then Recent / Drafts / Completed with live
  counts, then Profile and Settings, with the account and log-out pinned to the bottom.
  It collapses behind a hamburger and backdrop below 860px.
- **Stat row** — cards created against the limit (with a meter that turns amber when
  full), drafts in progress, completed cards, and the current plan with cards remaining.
- **Main column** — Recent, Drafts and Completed grids. New Card always leads Recent.
- **Right rail** — a subscription panel (state badge, plan, allowed/created/remaining,
  active-since, and the request button when unsubscribed) and **Recent Activity**.

**Recent activity** is derived in `CardManagerController::recentActivity()` from the
cards' own timestamps — created, edited, QR generated — rather than from a new audit
table. Creation and edit collapse into one entry when they are within a minute of each
other, so a freshly made card does not appear twice.

Each tile now shows the card's **label**, its theme, when it was last edited, a progress
meter, and **"Saved at step n of 10"** — so a draft says where it will resume before you
open it. Tiles carry Continue/Edit, an inline **rename**, and delete.

### 32.3 Save as Draft flow

Two entry points, one behaviour:

- The wizard topbar, available at every step.
- A panel on the QR step, sitting immediately **before** the Generate button.

Both open a modal asking for a **Card Label / Name** (80 characters, pre-filled with the
current label), then `POST /client/card/draft` → `BirthdayCardController@saveDraft`.

The step data itself is *not* saved here — every step already persists as it goes. What
this adds is the label and a record of progress:

```php
$card->title = $data['title'];
$card->current_step = max($card->current_step, $data['current_step']);
```

`max()` matters: stepping back to review Step 3 and saving must not rewind a card that
had reached Step 9. On success the client is returned to the dashboard, where the card
now appears under its chosen name.

### 32.4 Card editing / resume flow

Previously the wizard opened at Step 1 every time — `let currentStep = 1` and nothing
ever read `current_step`. A draft was preserved in the database but the client had to
click forward through every finished step to get back to their place.

Now:

```js
const SAVED_STEP = Math.min(10, Math.max(1, {{ current_step }}));
...
resumeAtSavedStep();   // last line of the load handler
```

`resumeAtSavedStep()` marks steps 1…n-1 done in the sidebar and calls `goToStep(n)`.
It runs **last**, after every restore block, because `goToStep()` fires each step's own
loaders and previews and those need the restored values already in place.

A card saved at the final stage has `current_step = 10`, so Edit opens straight on the
**QR step**, ready to generate or to keep editing.

> **Note on step numbering:** the request described the final stage as "Step 9 (QR
> Generation)". In this wizard QR Select is **Step 10** — Music took Step 9 in section 30.
> Resume uses the card's stored `current_step`, so a card saved at the end lands on the
> QR step whichever number it carries.

### 32.5 Theme persistence

This was a real bug, and the cause of "theme phir se select karna padta hai".

`selectTheme()` begins with `selectedVariant = null` — correct when a person picks a
theme by hand, since the old design no longer applies. But the restore code read the
saved variant *after* that call:

```js
selectTheme(savedTheme);
if (selectedVariant) selectVariant(selectedVariant);   // always null by now
```

`selectedVariant` was initialised from the card, then wiped a line later. The theme came
back; the design never did. Fixed by holding the saved value aside first:

```js
const savedTheme   = @json($card->theme ?? null);
const savedVariant = @json($card->variant ?? null);
if (savedTheme) {
    selectTheme(savedTheme);
    if (savedVariant) selectVariant(savedVariant);
}
```

Theme and design now both come back on reopen, and no step asks for them again.

### 32.6 Multiple-card handling

Unchanged from section 31.6 and re-verified here: cards are addressed by
`X-Card-Id` → `card_id` → session → newest draft, every lookup scoped to `Auth::id()`.
The draft save goes through the same `currentDraft()` resolution, so naming one card
cannot rename another. Theme, step data and label were each confirmed independent
across two cards owned by the same client.

### 32.7 Subscription / QR flow

Unchanged from section 31 — `saveStep10` still returns 403 `subscription_required`
without an active plan, and the gate still offers the plan picker. What is new is that
a client who cannot yet generate a QR now has somewhere to put the card: **Save as
Draft** sits directly above the Generate button, so the dead end is a parking place
instead.

### 32.8 Backend / database changes

No migrations were needed — `title` and `last_opened_at` already landed in
`2026_08_21_100002` (section 31.6), and `current_step` has existed since the original
cards table.

| File                          | Change                                                    |
| ----------------------------- | --------------------------------------------------------- |
| `BirthdayCardController`      | `saveDraft()` — label + furthest-step, `max()`-guarded     |
| `routes/web.php`              | `POST /client/card/draft` → `client.card.draft`            |
| `CardManagerController`       | `recentActivity()`; passes activity + latest request       |
| `client/cards.blade.php`      | Rebuilt as the dashboard                                   |
| `client/partials/card-tile`   | Label, theme, resume step, rename, Continue/Edit           |
| `client/dashboard.blade.php`  | Main Dashboard link, draft button + modal, resume, theme fix |

### 32.9 Verified

- Dashboard renders with Main Dashboard, activity feed, subscription panel, and both
  draft labels; tiles report "Saved at step 7" and "Saved at step 10" correctly.
- A card at `current_step = 7` reopens with `SAVED_STEP = 7`; one at 10 reopens on the
  QR step. Theme and variant both restore (`boy`/2 and `girl`/1).
- Save as Draft renames a card and advances `current_step` forward only — saving at
  step 4 left a card that had reached 7 at 7; saving at 9 moved it to 9.
- Re-saving Step 1 on a card at step 9 did not rewind it.
- Two cards owned by one client kept independent themes, headings and labels.
- All 17 pages render; the rendered wizard JavaScript parses clean.

---

## Final Summary

### Completed

- **Self-signup** (section 31.1) — landing-page Sign Up, client-created accounts,
  admin-side client creation removed; Disable/Enable untouched.
- **Super Admin reporting** (31.2) — plan, subscription state, card usage, device count
  per client, plus a full client detail page with live logged-in devices.
- **Plans and card limits** (31.3) — Rs 199/399/599 → 1/3/6 cards, enforced server-side,
  surfaced on the dashboard and in the wizard sidebar.
- **Subscription approval** (31.4) — request → Super Admin queue → approve/reject/revoke.
- **QR restriction** (31.5) — 403 without an active plan, with an in-wizard gate.
- **New / Recent / Drafts** (31.6) — card hub, per-card addressing, no cross-contamination.
- **Dashboard redesign** (32.2) — sidebar, stats, subscription panel, activity feed.
- **Save as Draft with labels** (32.3) — name a card and park it from any step.
- **Resume at last saved step** (32.4) — including landing on QR for finished cards.
- **Theme persistence fix** (32.5) — saved design no longer discarded on reopen.

### Still pending

- **Payment integration is not built.** Plan amounts are what a client asks to be put
  on, not something they have paid. Approval remains a manual Super Admin action.
- **No subscription expiry or renewal** — an approved plan stays active until revoked.
- **Device reporting is live sessions only** — there is no historical login audit.
- **Two pre-existing test failures** in `ClientPasswordResetTest`, environmental: the
  `pdo_sqlite` PHP extension is not installed. Unrelated to these changes.
