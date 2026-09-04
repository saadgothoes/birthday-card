# Anniversary Module

The anniversary card is a separate occasion that reuses the birthday card's
page/variant/gift URL shape. It is **static preview only** right now — there is
no controller, wizard step, or DB wiring yet. These routes exist so the public
story flow can be hung off them later without changing URLs.

See also: [THEME_AND_PAGES_REFERENCE.md §9](THEME_AND_PAGES_REFERENCE.md) for the
per-variant colour tokens.
 
---

## Routes

All in [routes/web.php](routes/web.php), right after the boy/girl equivalents.

| URL | View | Notes |
| --- | --- | --- |
| `/anniversary/page/{page}/{variant}` | `birthday.anniversary-page-{page}` (+`-{variant}` when variant ≠ 1) | Screens. `page` 1 lock · 2 letter · 3 gift select. `variant` 1-4 colour themes. |
| `/anniversary/page/{page}/{variant}/gift/{gift}/{giftPage}` | `birthday.anniversary-page-{page}-variant-{variant}-gift-{gift}-page-{giftPage}` | Gifts. `page` is always 3, `gift` 1-3, `giftPage` 1-4 is that gift's colour theme. |

Route names: `anniversary.page.variant`, `anniversary.page.gift`.

`?debug=true` on page 3 outlines the invisible gift hotspots.

---

## Variants (colour themes)

Same convention as boy/girl — variant `1` never appends a suffix.

| Variant | Mood | View suffix |
| --- | --- | --- |
| 1 | Taupe / charcoal | *(none)* |
| 2 | Maroon / gold | `-2` |
| 3 | Tan / copper (ivory & peach-gold) | `-3` |
| 4 | Blush / bright red & white | `-4` |

Page 3 image per variant: `anivar1.png` (v1), `anivar3.png` (v2), `anivar2.png`
(v3), `anivar4.png` (v4) — in `public/images/giftbox/`.

---

## Page 3 → gift navigation

`anniversary-page-3*.blade.php` has three invisible `.gift-area` hotspots (≥1024px)
and a pure-CSS 3-box tray (<1024px). `openGiftPage(n)` shows the loading screen,
then navigates to:

```
/anniversary/page/3/{variant}/gift/{n}/1
```

`{variant}` is hard-coded per file (page-3 → 1, page-3-2 → 2, page-3-3 → 3,
page-3-4 → 4). `{n}` is the box (1-3). **Gifts 1-3 all have views** now.

**Reveal flourish:** every page-3 file (boy ×2, girl ×2, anniversary ×4)
`@include`s [resources/views/birthday/partials/_gift_reveal_fx.blade.php](resources/views/birthday/partials/_gift_reveal_fx.blade.php)
right before `</body>`. It wraps `openGiftPage()` so a tap first plays a
light-burst / box-opening animation over the tapped box — a golden bloom + rays
+ sparkles + a glint on the image hotspot (desktop), and the CSS box's lid + bow
actually fly off with an inner glow (mobile) — then (~520 ms) hands off to the
unchanged loading screen + redirect. The partial needs no config; it reads the
tapped element's rect at runtime. The boy/girl page-3 files also had their
redirect fixed here (stale `/boy/gift-1/{n}` · `/girl/gift-1/{n}` →
`/{sex}/page/3/{variant}/gift/{n}/1`).

---

## Gift 1 — "Keepsake" memory page

- Shared design: [resources/views/birthday/partials/anniversary-gift-1.blade.php](resources/views/birthday/partials/anniversary-gift-1.blade.php)
- 16 thin wrappers: `anniversary-page-3-variant-{1..4}-gift-1-page-{1..4}.blade.php`,
  each just `@include`s the partial with a `giftTheme` (1-4).
- The gift looks the same across the four gift-screen variants — only `giftPage`
  (the theme, 1-4) changes the palette. This mirrors how the boy/girl gift files
  work (their variant-1 and variant-2 copies are identical too).

Layout: a couple heading, a taped 3-polaroid strip, a circular date medallion,
and a framed handwritten letter. Click any polaroid or the letter to zoom it.
Loosely based on boy gift 2 (photos + date + note) with a different composition.

### `giftPage` themes

| # | Name | bg gradient | paper | accent |
| - | --- | --- | --- | --- |
| 1 | Taupe & Charcoal | `#c7bca6 → #8f7f65` | `#f7f2ea` | `#141312` |
| 2 | Maroon & Gold | `#a35a56 → #5c1420` | `#f6ecd6` | `#a3792f` |
| 3 | Ivory & Peach Gold | `#d8b98c → #9c7c52` | `#faf5ea` | `#e0a865` |
| 4 | Bright Red & White | `#e3bcab → #c4917c` | `#fdf6f2` | `#e8281a` |

### Request params

Same names the public-story controller already feeds gift 1 / gift 2:

| Param | Meaning | Fallback |
| --- | --- | --- |
| `photo1`, `photo2`, `photo3` | memory photos | drawn scene |
| `name_first`, `name_second` | the couple | Ayesha / Bilal |
| `cal_month`, `cal_day` | medallion date | September / 14 |
| `years` | "n years" line | 5 |
| `message`, `signed` | letter body + signature | sample text |
| `theme` | overrides `giftTheme` when included without one | 1 |

---

## Gift 2 — "Scratch to reveal" memory cards

- Shared design: [resources/views/birthday/partials/anniversary-gift-2.blade.php](resources/views/birthday/partials/anniversary-gift-2.blade.php)
- 16 thin wrappers: `anniversary-page-3-variant-{1..4}-gift-2-page-{1..4}.blade.php`,
  each `@include`s the partial with a `giftTheme` (1-4).

A stack of covered cards. Each card is a foil `<canvas>` panel the recipient
scratches away with a finger / mouse ("Scratch to reveal" hint + animated
thumb). A `destination-out` brush erases the foil along the pointer path;
`getImageData` samples the clear area every few moves and once it passes ~55%
the rest fades on its own. The card underneath shows a memory: an **image**
(the uploaded `photo`, else a themed drawn scene — meet / trip / home / ring),
then date · title · note. **Next** advances. The last card is a **letter** —
drawn envelope-and-heart scene + the anniversary `message` + `signed`, its
button reads "Read again" (loops to card 1). Progress dots track position.

### `giftPage` themes

| # | Name | bg gradient | foil | accent |
| - | --- | --- | --- | --- |
| 1 | Silver Taupe | `#c7bca6 → #8f7f65` | `#cfc7b6 / #efe9dc / #a29a89` | `#141312` |
| 2 | Maroon & Gold | `#a35a56 → #5c1420` | `#b8892f / #ecce85 / #8f6420` | `#8b1e28` |
| 3 | Ivory & Peach Gold | `#d8b98c → #9c7c52` | `#e0a865 / #f4d8ac / #c2853f` | `#b5673c` |
| 4 | Bright Red & White | `#e3bcab → #c4917c` | `#e8281a / #ff8478 / #b81c11` | `#e8281a` |

### Request params

| Param | Meaning | Fallback |
| --- | --- | --- |
| `name_first`, `name_second` | header + final card title | Ayesha / Bilal |
| `message`, `signed` | the final card (after the last memory) | sample text |
| `memories` | JSON `[{date,title,text,photo}]` — overrides the flat form | — |
| `mem1_date` … `mem6_*` | flat form (`_date` / `_title` / `_text` / `_photo`) | 4 demo memories |
| `theme` | overrides `giftTheme` when included without one | 1 |

Max 6 memory cards + 1 final card.

---

## Gift 3 — "Pop-up Book"

- Shared design: [resources/views/birthday/partials/anniversary-gift-3.blade.php](resources/views/birthday/partials/anniversary-gift-3.blade.php)
- Drawn fallback scene: [resources/views/birthday/partials/_anniversary_gift3_scene.blade.php](resources/views/birthday/partials/_anniversary_gift3_scene.blade.php) (`$kind` = `meet` / `trip` / `letter`)
- 16 thin wrappers: `anniversary-page-3-variant-{1..4}-gift-3-page-{1..4}.blade.php`,
  each `@include`s the partial with a `giftTheme` (1-4).

A closed leather book centre-stage (couple's initials + wax-seal heart). Tap it
(or **Open the book**) and the **front cover swings open on a LEFT hinge** (slow,
~1.25s). Inside are **three spreads** — *How it began* · *The years between* ·
*Still us* — each reached by a slow page-turn (a `.turner` leaf flips over the
base, ~1.15s). On every spread a paper-craft scene stands up in staggered layers
(a `rotateX(-90deg) → 0` fold with `translateZ` depth): photo cut-outs, a date
tag, a "n years" ring, an envelope-heart. Tap a standing photo to zoom it.
On spread 3 the button is **Close the book** — the open front cover snaps shut
with no sweep of its own, so the **only motion is the back cover swinging in
from the RIGHT hinge** onto "The End"; **Read again** then restarts. Pure CSS 3D
+ vanilla JS, no libraries. Honors `prefers-reduced-motion`. Desktop gets a
subtle `perspective-origin` parallax.

State machine (JS): `cover → s1 → s2 → s3 → end → (reset) cover`, driven by
`setTimeout` timing (not `transitionend`, which does not fire reliably when a
transition starts out of a CSS animation). Each motion is a two-step transition
(a short *lift* off the page, then the *swing*). Spreads only raise their
pop-ups once `.revealed` is added after the covering page settles, so the
stagger always reads.

### `giftPage` themes

| # | Name | bg gradient | paper | cover | accent |
| - | --- | --- | --- | --- | --- |
| 1 | Taupe & Charcoal | `#c7bca6 → #8f7f65` | `#f7f2ea` | `#5a5147 → #2f2b26` | `#141312` |
| 2 | Maroon & Gold | `#a35a56 → #5c1420` | `#f6ecd6` | `#7a1f28 → #4a0f16` | `#a3792f` |
| 3 | Ivory & Peach Gold | `#d8b98c → #9c7c52` | `#faf5ea` | `#c08a4e → #7f5a30` | `#e0a865` |
| 4 | Bright Red & White | `#e3bcab → #c4917c` | `#fdf6f2` | `#e8281a → #a3140b` | `#e8281a` |

### Request params

| Param | Meaning | Fallback |
| --- | --- | --- |
| `photo1` | spread 1 standing cut-out | drawn "meet" scene |
| `photo2` | spread 2 standing cut-out | drawn "trip" scene |
| `photo3` | spread 3 standing cut-out | drawn envelope-heart |
| `name_first`, `name_second` | couple (header, cover initials, ex-libris, The End) | Ayesha / Bilal |
| `cal_month`, `cal_day` | spread-1 date tag | September / 14 |
| `years` | spread-2 ring "n years" | 5 |
| `message`, `signed` | spread-3 letter body + signature | sample text |
| `line1`, `line2` | one-liners under spreads 1 & 2 | sample text |
| `theme` | overrides `giftTheme` when included without one | 1 |
| `open` | `1`/`s1`/`s2`/`s3`/`end` — deep-link straight to a step (preview + future wiring) | — |

---

## Ending page — "Blow out the candles"

- Shared design: [resources/views/birthday/partials/anniversary-ending.blade.php](resources/views/birthday/partials/anniversary-ending.blade.php)
- 4 thin wrappers: `anniversary-page-4{,-2,-3,-4}.blade.php` — each `@include`s
  the partial with an `endingTheme` (1-4). Same variant convention as boy/girl
  (`/anniversary/page/4/{variant}`; variant 1 never appends a suffix).
- Boy/girl use one self-contained file per theme (`boy-page-4*.blade.php`, an
  envelope + handwritten letter). Anniversary uses the shared-partial shape
  instead, matching the anniversary gifts.

Two lit taper candles on an engraved base (`{years} years`). Tap anywhere (or
the candles) → the flames blow out (a puff, a rising curl of smoke, the room
dims via a `body::after` vignette) → a closing note fades up: the anniversary
`message`, the years line, `signed`, and "the end". **Relight** replays it.
Ambient: drifting sparks + a few floating hearts. Pure CSS/JS, no libraries;
honors `prefers-reduced-motion`.

### `endingTheme` palettes

| # | Name | bg gradient (dim / candlelit) | wax | accent | flame glow |
| - | --- | --- | --- | --- | --- |
| 1 | Taupe & Charcoal | `#3b362d → #211f1a → #14130f` | `#f7f2ea` | `#c9bfa8` | warm amber |
| 2 | Maroon & Gold | `#4d121a → #280910 → #170509` | `#f6ecd6` | `#c9a75c` | warm amber |
| 3 | Ivory & Peach Gold | `#5b4632 → #332619 → #1f1710` | `#faf5ea` | `#e0a865` | warm amber |
| 4 | Bright Red & White | `#5c1712 → #2c0a08 → #170403` | `#fdf6f2` | `#ff9a89` | warm amber |

### Request params

| Param | Meaning | Fallback |
| --- | --- | --- |
| `name_first`, `name_second` | the couple | Ayesha / Bilal |
| `years` | base engraving + closing line | 5 |
| `message`, `signed` | the closing note + signature | sample text |
| `heading` | kicker over the names | Our Anniversary |
| `wish_label`, `tap_label`, `end_label` | the three bits of chrome | Make a wish / tap to blow them out / the end |
| `theme` | overrides `endingTheme` when included without one | 1 |
| `preview_stage=out` | skip straight to the blown-out note (preview / wiring) | — |

---

## TODO (later functionality)

- Controller + wizard step so an anniversary card can be built and saved.
- Wire `PublicStoryController` (`theme === 'anniversary'`) to serve pages 1-4
  and the gifts through `/c/{slug}`.
