# Dashboard Wizard — Steps 3 to 6 (Dynamic Card Builder)

Work log for the client dashboard card-builder wizard: making Steps 3, 4, 5 and 6 fully
dynamic with real live previews, and making the underlying card templates data-driven.

**Scope of this document:** everything up to and including Gift 2 (Step 6).
Gift 3 and the link/QR publish flow are **not** covered here — that work was reverted
and is not present in the codebase.

**Corresponding commit:** `d9fa1c1` — 42 files changed, 1644 insertions, 584 deletions.

---

## 1. Database

Two migrations were added.

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
to `'array'`.

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

All methods operate on `currentDraft()` — the client's latest unpublished `BirthdayCard`,
created on demand. Photo uploads merge into the existing photo array rather than replacing
it, so re-saving a step without re-picking every image does not wipe previously uploaded
photos. Replaced files are deleted from disk.

Uploads are stored on the `public` disk:
- `birthday-cards/profile/` — Step 2 profile photo
- `birthday-cards/gift1/` — Gift 1 photos
- `birthday-cards/gift2/` — Gift 2 photos

### Routes — `routes/web.php`

Added inside the authenticated `client.` prefix group:

```php
Route::post('/card/step3', [BirthdayCardController::class, 'saveStep3'])->name('card.step3');
Route::post('/card/step4', [BirthdayCardController::class, 'saveStep4'])->name('card.step4');
Route::post('/card/step5', [BirthdayCardController::class, 'saveStep5'])->name('card.step5');
Route::post('/card/step6', [BirthdayCardController::class, 'saveStep6'])->name('card.step6');
```

---

## 3. Dashboard Wizard

`resources/views/client/dashboard.blade.php` — restructured to **7 steps**
(`const totalSteps = 7`):

1. Choose Theme
2. Set Lock Code
3. Welcome Screen
4. Gift Box Screen
5. Gift 1
6. Gift 2
7. Generate & Share

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

## 7. Not Included

The following were **not** part of this work and are not in the codebase:

- **Gift 3** — no wizard step, no `gift3_data` column, templates not made dynamic.
- **Ending page** — the `page-4` letter templates exist but are static and unwired.
- **Link / QR generation** — Step 7's `generateCard()` is still a client-side stub that
  fabricates a fake slug. No publish endpoint, no `slug` persistence, no QR library.
- **Public card viewing flow** — there is no `/c/{slug}` route, no PIN-unlock logic
  (the numpad on page 1 is decorative), and no page-to-page navigation in the published
  card. The gift-box screen's `openGiftPage()` still points at a dead `/boy/gift-1/…` URL
  that matches no registered route.

---

## 8. Environment Notes

- **`vendor/endroid/` is an orphaned directory.** A QR package was installed during the
  reverted work; `composer.json` no longer references it but the vendor folder remains.
  Harmless, but `composer install` will clear it.
- **Shell `DB_*` environment variables override `.env`.** The shell profile exports
  `DB_CONNECTION=pgsql`, `DB_HOST=postgres`, `DB_DATABASE=munsif` (a different project),
  which take precedence over `.env` and cause `php artisan` to fail with a connection error.
  Workaround when running artisan commands:
  ```bash
  env -u DB_CONNECTION -u DB_HOST -u DB_PORT -u DB_DATABASE -u DB_USERNAME -u DB_PASSWORD \
    php artisan migrate:status
  ```
- **`boy-page-4-3.blade.php` is corrupted** — it contains three complete, identical HTML
  documents concatenated into one file (2429 lines instead of ~810). It needs to be
  truncated to a single copy before the ending page can be used.
- **Some uploaded photos were lost.** During test-data cleanup, the gift/profile upload
  folders were cleared wholesale rather than by specific filename, which deleted real
  photos belonging to existing cards (user IDs 2 and 8). The database rows still reference
  those paths, but the image files are gone and must be re-uploaded.
