# Theme & Pages Reference

A single map of every public card page, its URL, its Blade view, and the colour
palette each theme uses. Covers **both sides** (`boy` / `girl`), **both variants**
(the two colour themes), every **gift sub-theme**, the **ending-page designs**, the
**QR designs**, and the **app-chrome pages** (dashboard, auth, admin, landing).

All hex values are lifted straight from the templates' own `:root` custom properties
(or, where a template has no tokens, its dominant background/accent colours).

---

## 1. How the pages fit together

```
/{side}/page/{page}/{variant}                              → standalone screen
/{side}/page/{page}/{variant}/gift/{gift}/{giftTheme}      → a gift, in one of 4 colour themes
```

| side  | `boy` or `girl` (plus new `anniversary` — page 1 only, 4 colour variants) |
| ----- | --------------- |
| page  | `1` lock · `2` welcome · `3` gift screen · `4` ending |
| variant | `1` = default theme · `2` = alternate colour theme (route appends `-{variant}` to the view name; `1` never appends) |
| gift  | `1` polaroid board · `2` couple memory / letter · `3` "Our Story" flip-book |
| giftTheme | `1`–`4` — each is a full colour re-skin of that gift (URL calls it a "page", the wizard calls it a "theme") |

Route definitions: [routes/web.php:116-149](routes/web.php#L116-L149)
Full URL list: [BIRTHDAY_CARD_ROUTES.md](BIRTHDAY_CARD_ROUTES.md)
Wizard / data flow: [DASHBOARD_WIZARD_DOCUMENTATION.md](DASHBOARD_WIZARD_DOCUMENTATION.md)

### Page → URL → view

| Page | Boy V1 | Boy V2 | Girl V1 | Girl V2 |
| ---- | ------ | ------ | ------- | ------- |
| 1 Lock    | `/boy/page/1/1` · `boy-page-1`    | `/boy/page/1/2` · `boy-page-1-2`    | `/girl/page/1/1` · `girl-page-1`    | `/girl/page/1/2` · `girl-page-1-2`    |
| 2 Welcome | `/boy/page/2/1` · `boy-page-2`    | `/boy/page/2/2` · `boy-page-2-2`    | `/girl/page/2/1` · `girl-page-2`    | `/girl/page/2/2` · `girl-page-2-2`    |
| 3 Gifts   | `/boy/page/3/1` · `boy-page-3`    | `/boy/page/3/2` · `boy-page-3-2`    | `/girl/page/3/1` · `girl-page-3`    | `/girl/page/3/2` · `girl-page-3-2`    |
| 4 Ending  | `/boy/page/4/1` · `boy-page-4`    | `/boy/page/4/2..4` · `boy-page-4-2..4` | `/girl/page/4/1` · `girl-page-4` | `/girl/page/4/2..4` · `girl-page-4-2..4` |

> Ending page **variant** and ending-page **design** are the same axis here — there are
> 4 ending designs per side (`page-4`, `-4-2`, `-4-3`, `-4-4`), see §7.

Gift pages (page 3 only), e.g. `boy-page-3-variant-1-gift-2-page-3` =
boy · gift-screen variant 1 · gift 2 · colour theme 3.

**Anniversary module** (static, lock screen only for now):
`/anniversary/page/1/{1..4}` → `anniversary-page-1`, `-1-2`, `-1-3`, `-1-4` — same
`.boy2-*` lock-screen markup as boy page 1, re-skinned 4 ways (§9).

**Legacy / unrouted files:** `boy-gift-1-1..3`, `girl-gift-1-1..3`,
`girl-page-1-1`, `girl-page-2-1` exist in `resources/views/birthday/` but no route
resolves them (variant `1` never appends a suffix). Safe to ignore for the live flow.

---

## 2. Boy side — core pages

### Variant 1 — "Candlelit Gold" (default)

Dark, warm, candle-lit. Amber/gold ink on near-black brown.

| Token | Value | Role |
| ----- | ----- | ---- |
| bg gradient | `#1a0a00 → #2d1200 → #0f0500` | page background |
| panel | `#3d1a00 → #1a0800` | frames / cards |
| gold ink | `#f0c060` | headings |
| gold bright | `#fff3c4` | highlights |
| gold mid | `#e8b84a` / `#f6d976` | body accent |
| border | `#c8922a` | rules, frames |
| rule glow | `rgba(200,146,42,.5)` | dividers |

Page 3 gift room adds a leather + polished-gold set: `#e9d6a3 → #ddc48a` (leather),
`#7a5411 → #f6d976 → #fff3c4` (gold rail), card `#2b2b2b → #0a0a0a`.

### Variant 2 — "Ice Blue"

Light, airy, pale-blue paper with royal-blue ink.

| Token | Value | Role |
| ----- | ----- | ---- |
| bg gradient | `#f6fbff → #e8f3ff → #c5deff` | page background |
| panel | `#edf6ff → #cfe5ff` | frames / cards |
| blue ink | `#4e74d6` | headings |
| blue mid | `#5f83dd` | body accent |
| button / accent | `#77a7ff → #5f7dff` (gradient), solids `#6fa8ff`, `#9ed0ff`, `#bddbff` | CTAs, chips |
| border | `#7ab0ff` / `#6fa8ff` | rules, frames |
| rule glow | `rgba(90,140,255,.8)` | dividers |

Page 3 gift room variant 2 swaps to a **red + blue** set: box `#6e1f1f → #591616`,
lid rail `#7a1414 → #f34747 → #ff8f8f`, gift card `#6ea3f0 → #2c56a8`.

---

## 3. Girl side — core pages

### Variant 1 — "Blush Pink" (default)

Soft, light, rose on cream.

| Token | Value | Role |
| ----- | ----- | ---- |
| bg gradient | `#fff0f5 → #fce8f0 → #f5e0ec` | page background |
| panel | `#ffd6e8 → #f0a8c8` | frames / cards |
| deep rose ink | `#9b4d76` / `#7a3460` / `#8b2252` | headings |
| pink accent | `#e060a0` | primary accent, CTAs |
| pink mid | `#c4709a` / `#d4829a` | body accent, rules |
| pink light | `#f090b0` / `#f5d6e8` | soft fills |
| inverse text | `#fff5f8` | text on pink |

Page 3 gift room V1: box `#d94a85 → #c33d75`, ribbon
`#d84e93 → #ff9dc7 → #ffd6eb`, gift card `#cdd8fb → #8aa3ec`, panel text `#7a2144`.

### Variant 2 — "Midnight Magenta"

Dark, glossy, hot-pink on near-black plum.

| Token | Value | Role |
| ----- | ----- | ---- |
| bg gradient | `#1a0a1a → #2d1230 → #0f050c` (P1) · `#09090f → #11111a → #1a1020` (P2) | page background |
| panel | `#2d1230 → #1a0818` | frames / cards |
| hot-pink ink | `#ff69b4` / `#ff1493` | headings |
| pink accent | `#ff7ba5` / `#ff7aa8` | primary accent, CTAs |
| pink soft | `#ffb6c1` / `#ff9bbb` / `#ff8eb7` | highlights |
| magenta border | `#c71585` | rules, frames |
| white | `#ffffff` | key text |

Page 3 gift room V2: box `#291a45 → #1e1236`, ribbon
`#9c2e8f → #e363cf → #ffb4f0`, gift card `#6b5aa8 → #362c62`, panel text `#ffe6f8`.

---

## 4. Gift 1 — polaroid photo board (4 colour themes each)

Gift 1 templates are scene-drawn (no design tokens); values below are the dominant
background + accent per theme. Params: `photo1`–`photo3` (girl also `cal_month`,
`cal_day`, `message`).

### Boy · `boy-page-3-variant-{1,2}-gift-1-page-{1..4}`

| Theme | Mood | Background | Key accents |
| ----- | ---- | ---------- | ----------- |
| 1 | Warm amber | `#111` / `#1a1008` | tan `#c8a882`, cream `#f0d090`, red pop `#c0332a` |
| 2 | Dusty mauve | `#150e12` | rose `#b56a7a`, blush `#dbb0b8`, cream `#fcf0f0` |
| 3 | Cocoa + pink | `#111` / `#1a1008` | tan `#c8a882`, pink pop `#f0b0d0`, red `#c0332a` |
| 4 | Muted slate-violet | `#0c0a0e` / `#1a1220` | greys `#8a7a8a`, `#6a5a7a`, plum `#3a2a42` |

### Girl · `girl-page-3-variant-{1,2}-gift-1-page-{1..4}` (tokenised)

| Token | Theme 1 Rose | Theme 2 Sky | Theme 3 Violet | Theme 4 Coral |
| ----- | ------------ | ----------- | -------------- | ------------- |
| `--bg-dark-1` | `#3B1626` | `#1E3045` | `#2F2343` | `#47302C` |
| `--bg-dark-2` | `#4E1E33` | `#2D4663` | `#43335D` | `#62433E` |
| `--bg-dark-3` | `#200D18` | `#101B28` | `#1D162A` | `#2A1A17` |
| `--paper-light` | `#FFF3F7` | `#F9FCFF` | `#FCFAFF` | `#FFF9F6` |
| `--paper-mid` | `#FBD9E6` | `#E6F3FF` | `#EFE6FF` | `#FFE8DE` |
| `--accent-1` | `#F472A0` | `#72C7FF` | `#B57CFF` | `#FF9A8B` |
| `--accent-2` | `#D9558F` | `#4EA7E8` | `#8F5BE6` | `#F56F7B` |
| `--accent-3` | `#C99CE0` | `#B8D8FF` | `#F7A7D7` | `#FFC6A5` |
| `--accent-gold` | `#F6C6D9` | `#EAF7FF` | `#F2D9FF` | `#FFE5CF` |

---

## 5. Gift 2 — couple memory (boy) / handwritten letter (girl)

### Boy · `boy-page-3-variant-{1,2}-gift-2-page-{1..4}`

| Token | Theme 1 Amber | Theme 2 Cocoa | Theme 3 Navy | Theme 4 Slate-Teal |
| ----- | ------------- | ------------- | ------------ | ------------------ |
| `--bg-1` | `#3a2a1a` | `#2a1a0e` | `#1c2533` | `#25272f` |
| `--bg-2` | `#1a1008` | `#0e0804` | `#0f1624` | `#14161e` |
| `--accent` | `#c8935a` | `#a0703c` | `#4d9fff` | `#3eb8a6` |
| `--btn-bg` | `#f0d0a0` | `#f0c090` | `#f4a261` | `#e89c5e` |
| `--card-bg` | `#1a1008` | `#1e120a` | `#2a3749` | `#2f323f` |
| `--paper` | `#f6ecd9` | `#f8ecd8` | `#f1ede6` | `#f2ede6` |
| `--paper-2` | `#eaddc0` | `#ecdcbc` | `#e0d9cc` | `#e2d9cc` |
| `--ink` | `#2a1c10` | `#2a1c10` | `#1e2a3f` | `#1f222e` |
| `--heart` | `#8a3a2a` | `#7a4a1e` | `#b53e3e` | `#c15b4e` |

### Girl · `girl-page-3-variant-{1,2}-gift-2-page-{1..4}`

| Token | Theme 1 Lilac | Theme 2 Sky | Theme 3 Coral | Theme 4 Mint |
| ----- | ------------- | ----------- | ------------- | ------------ |
| `--bg-dark` | `#221A35` | `#182B40` | `#402520` | `#17332E` |
| `--bg-light` | `#3D2F59` | `#284A69` | `#68423A` | `#2D5A52` |
| `--card` | `#564174` | `#356283` | `#86574C` | `#3F7267` |
| `--primary` | `#A97CFF` | `#73C7FF` | `#FFA78F` | `#73D7B4` |
| `--secondary` | `#E6D8FF` | `#D9F0FF` | `#FFE3D8` | `#DCF8EE` |
| `--accent` | `#C99BFF` | `#9EDCFF` | `#FFC3AE` | `#A8F0D4` |
| `--gold` | `#F3D8FF` | `#EAF8FF` | `#FFF0E6` | `#F1FFF9` |
| `--text-dark` | `#473564` | `#28435E` | `#6B443A` | `#2D564E` |
| `--paper` | `#FCF8FF` | `#F9FDFF` | `#FFF9F6` | `#F9FFFD` |

---

## 6. Gift 3 — "Our Story" flip-book

### Boy · `boy-page-3-variant-{1,2}-gift-3-page-{1..4}`

Leather cover + polished gold, re-skinned four ways. Gold stays warm across all four.

| Token | Theme 1 Leather | Theme 2 Steel | Theme 3 Forest | Theme 4 Charcoal |
| ----- | --------------- | ------------- | -------------- | ---------------- |
| `--cream` | `#F7F2EA` | `#F6F4EF` | `#F5F3EC` | `#F7F5F1` |
| `--sand` | `#E9D8C5` | `#D8D6D2` | `#DDD6C9` | `#D9D6D3` |
| `--tan` | `#D2B48C` | `#9FA9B7` | `#97A68E` | `#777777` |
| `--leather` | `#8B5E3C` | `#31445F` | `#355845` | `#242424` |
| `--espresso` | `#3B2A20` | `#182334` | `#1A2B22` | `#101010` |
| `--gold` | `#C9A24B` | `#D4AF37` | `#C8A94E` | `#D4AF37` |
| `--gold-light` | `#E8CE8B` | `#F0D98B` | `#EFD78F` | `#F4D97A` |
| `--paper-white` | `#FFFDF8` | `#FEFEFC` | `#FEFCF8` | `#FFFFFF` |
| `--ink` | `#2E2013` | `#1B2330` | `#172019` | `#181818` |
| `--bg-1` | `#4A3626` | `#23344F` | `#294838` | `#2A2A2A` |
| `--bg-2` | `#2C1E15` | `#141E2D` | `#18281F` | `#171717` |
| `--bg-3` | `#1C130D` | `#090E15` | `#0B120E` | `#090909` |

### Girl · `girl-page-3-variant-{1,2}-gift-3-page-{1..4}`

Camera-roll / photo-frame set. `--frame-bezel #1c1214`, `--frame-titanium #cbb9ac`
are shared across all four.

| Token | Theme 1 Caramel | Theme 2 Rose | Theme 3 Lavender | Theme 4 Peach |
| ----- | --------------- | ------------ | ---------------- | ------------- |
| `--bg-dark` | `#2a1a1c` | `#2a171f` | `#201a2a` | `#2c1c14` |
| `--bg-light` | `#fbeeea` | `#fdeef2` | `#f3eefc` | `#fff1e6` |
| `--primary` | `#d9a273` | `#e8899f` | `#a992d9` | `#f0a878` |
| `--secondary` | `#e8b4ac` | `#f3b6c4` | `#cbb6ec` | `#f6c9a0` |
| `--accent` | `#9c5b45` | `#a84c68` | `#6a4f9c` | `#b85f3a` |
| `--text` | `#3a2420` | `#3a2028` | `#271f38` | `#3d2416` |
| `--card` | `#fffaf6` | `#fffafb` | `#fbf9ff` | `#fffaf4` |

---

## 7. Ending page — 4 designs per side

Names & blurbs from `BirthdayCardController::ENDING_THEMES`
([app/Http/Controllers/Client/BirthdayCardController.php:692](app/Http/Controllers/Client/BirthdayCardController.php#L692)).

### Boy

| # | Name | View | `--bg` | `--primary` | `--secondary` | `--accent` / ink | `--paper` |
| - | ---- | ---- | ------ | ----------- | ------------- | ---------------- | --------- |
| 1 | Cool Steel | `boy-page-4`   | `#EEF3F8` | `#6E9FC9` | `#2C5A80` | `#16233A` | `#FBFDFF` |
| 2 | Graphite Ice | `boy-page-4-2` | `#0D1117` | `#4FA8D8` | `#274B67` | `#BFD9EC` | `#161B22` |
| 3 | Midnight Gold | `boy-page-4-3` | `#0B0D12` | `#C9A227` | `#6E5518` | `#E8C468` | `#1B1F27` |
| 4 | Slate Emerald | `boy-page-4-4` | `#EEF2EF` | `#4C9A79` | `#2E5945` | `#1C2521` | `#FBFDFC` |

### Girl

| # | Name | View | `--bg` | `--bg-deep` | `--primary` | `--secondary` | `--accent` | `--ink` | `--card` |
| - | ---- | ---- | ------ | ----------- | ----------- | ------------- | ---------- | ------- | -------- |
| 1 | Blush Rose | `girl-page-4`   | `#FFF3F6` | `#FBDDE6` | `#E8899F` | `#C96A85` | `#7A3247` | `#5A2637` | `#FFFAFB` |
| 2 | Lilac Dusk | `girl-page-4-2` | `#F5F1FD` | `#E2D8F6` | `#A98BD8` | `#7A5BAE` | `#3F2A63` | `#3A2758` | `#FDFBFF` |
| 3 | Rose Gold Noir | `girl-page-4-3` | `#1C1116` | `#2E1922` | `#E7A9AE` | `#C58490` | `#F6DCE1` | `#F6DCE1` | `#2A1922` |
| 4 | Plum Midnight | `girl-page-4-4` | `#150F22` | `#241634` | `#C7A6F0` | `#9E7BD0` | `#EADFFB` | `#EADFFB` | `#221836` |

Girl petal accents: `--petal-a/b/c` + `--leaf` + `--stem` per design (see
[girl-page-4.blade.php:52-56](resources/views/birthday/girl-page-4.blade.php#L52-L56)).

---

## 8. QR designs — 4 per side

Names from `BirthdayCardController::QR_THEMES`
([app/Http/Controllers/Client/BirthdayCardController.php:719](app/Http/Controllers/Client/BirthdayCardController.php#L719)).
Rendered by `app/Support/QrRenderer.php` (SVG).

| # | Boy | Girl |
| - | --- | ---- |
| 1 | Midnight Navy — classic squares, ivory card | Blush Petal |
| 2 | Steel Glow — rounded modules, blue gradient | Lilac Confetti |
| 3 | Graphite Ice — dark card, ice-blue dots | Rose Gold Noir |
| 4 | Blueprint — dashed frame, plain squares | Plum Midnight |

> Girl side registered but not all wired into the wizard — check the `available`
> flags before relying on it.

---

## 9. Anniversary module (static — no controller/wizard yet)

Route: `/anniversary/page/{page}/{variant}` → `anniversary-page-{page}` (+`-{variant}`
if variant ≠ 1). Same variant convention as boy/girl. `?heading=` / `?message=` /
`?photo=` params supported.

### Page 1 — lock screen

Same `.boy2-*` markup as `boy-page-1` (arch photo frame + 4-box code + numpad),
re-skinned 4 ways.

| # | View | Mood | Page bg gradient | Accent | Text ink | Photo frame bg |
| - | ---- | ---- | ---------------- | ------ | -------- | -------------- |
| 1 | `anniversary-page-1`   | Taupe / charcoal | `#c7bca6 → #b3a68c → #8f7f65` | `#141312` | `#1c1a17` | cream `#f2ece0 → #d5cdb7` |
| 2 | `anniversary-page-1-2` | Maroon / gold    | `#a35a56 → #7a3d3c → #5c1420` | `#a3792f` gold / `#c9a75c` | `#f0d9a5` | dark maroon `#7a1620 → #4a0f18` |
| 3 | `anniversary-page-1-3` | Tan / copper     | `#d8b98c → #b08f62 → #9c7c52` | `#c98a4a` | `#4a3220` | cream `#f2ecdf → #e0d6bd` |
| 4 | `anniversary-page-1-4` | Blush / red      | `#e3bcab → #cf9880 → #c4917c` | `#e8281a` | `#5c1712` | red `#e8281a → #a01813` |

### Page 2 — "Engraved Letter" (foil-shimmer card, `.om-*` markup)

Radial `#d3c7ac → --bg-top → --bg-bottom` ground; engraved card with drawn gold
frame, monogram, and a shine layer that tracks the cursor (ambient loop on touch).

| # | View | Mood | `--bg-top / --bg-bottom` | `--cream` (card) | `--ink` | `--gold` |
| - | ---- | ---- | ----------------------- | ---------------- | ------- | -------- |
| 1 | `anniversary-page-2`   | Taupe / charcoal      | `#c7bca6` / `#8f7f65` | `#f2ece0` | `#141312` | `#a6813f` |
| 2 | `anniversary-page-2-2` | Red & gold            | `#a35a56` / `#5c1420` | `#8b1e28` | `#f2ece0` | `#a3792f` |
| 3 | `anniversary-page-2-3` | Ivory & peach-gold    | `#d8b98c` / `#9c7c52` | `#f2ecdf` | `#33281a` | `#e0a865` |
| 4 | `anniversary-page-2-4` | Bright red & white    | `#e3bcab` / `#c4917c` | `#e8281a` | `#f5efe8` | `#f5efe8` |

### Page 3 — gift selection (`boy-page-3` clone)

Exact `boy-page-3` structure: full-bleed `anivarN.png` with 3 invisible `.gift-area`
hotspots (`openGiftPage(1..3)` → loading screen → `/anniversary/gift-1/{n}`) on
≥1024px; pure-CSS 3-box tray (`.mgb-*`) below 1024px; a **static** `.next-btn`.
Colours driven by a `:root` token block — only that block + `<img src>` + `<title>`
differ between the 4 files. `?debug=true` outlines the hotspots.

| # | View | Image | Theme | Box / ribbon |
| - | ---- | ----- | ----- | ------------ |
| 1 | `anniversary-page-3`   | `anivar1.png` | Taupe / charcoal   | cream box `#f7f2ea` · black ribbon `#2b2925` |
| 2 | `anniversary-page-3-2` | `anivar3.png` | Red & gold         | dark-red box `#8b1e28` · gold ribbon `#a3792f`/`#f0d9a5` |
| 3 | `anniversary-page-3-3` | `anivar2.png` | Ivory & peach-gold | ivory box `#faf5ea` · peach-gold ribbon `#e0a865` |
| 4 | `anniversary-page-3-4` | `anivar4.png` | Bright red & white | red box `#e8281a` · white ribbon `#f5efe8` |

---

## 10. App-chrome pages (not the card itself)

### Client — card builder / dashboard family

`client/dashboard`, `client/profile`, `client/settings` — warm cream shell:

| Token | Value |
| ----- | ----- |
| `--bg` | `#fdf6f0` |
| `--surface` | `#ffffff` |
| `--surface2` | `#fef9f5` |
| `--border` | `#f0e6da` |
| `--text` | `#2d1f14` |
| `--text-muted` | `#9c7c62` |
| `--accent-boy` | `#4f8ef7` |
| `--accent-girl` | `#f76fa1` |

### Client — auth (`client/login`, `client/register`, `client/forgot-password`, `client/reset-password`)

| Token | Value |
| ----- | ----- |
| `--bg` | `#f7f5fc` |
| `--surface` | `#ffffff` |
| `--border` | `#e7e0fa` |
| `--text` | `#120d1c` |
| `--text-muted` | `#6b6478` |
| `--accent` | `#8B5CF6` |
| `--accent-soft` | `#f3edfe` |

### Client — card hub (`client/cards`)

Violet, same family as auth: `--bg #f6f4fb`, `--surface #ffffff`,
`--accent #8B5CF6`, `--accent2 #a78bfa`, `--green #10b981`, `--amber #f59e0b`,
`--text #140f1f`, `--border #e9e3f8` / `--border2 #d9cdf7`.

### Admin (`admin/dashboard` etc.)

| Token | Value |
| ----- | ----- |
| `--bg` | `#f4f6fb` |
| `--surface` | `#ffffff` |
| `--surface2` | `#f8faff` |
| `--border` | `#e4e9f4` / `#d0d8ee` |
| `--text` | `#111827` |
| `--text-muted` | `#6b7a99` |

`admin/login` uses its own: `--primary #0f172a`, `--accent #6366f1`, `--bg #f8fafc`,
`--card #ffffff`, `--text #1e293b`, `--border #e2e8f0`.

### Landing page (`welcome` + `landing/*`, tokens in `resources/css/landing.css`)

| Token | Value |
| ----- | ----- |
| `--purple` | `#8B5CF6` |
| `--purple-strong` | `#7c3aed` |
| `--white` | `#ffffff` |
| `--ink` | `#120d1c` |
| `--ink-soft` | `rgba(18,13,28,.62)` |
| `--line` | `rgba(18,13,28,.09)` |

Fonts: Playfair Display (display) + DM Sans (body).

### Public story shell (`story/shell`)

Injects a numpad glow keyed to the card side:

| | `--np-a` | `--np-b` |
| - | ------- | ------- |
| girl | `#ff9ec0` | `#ffc978` |
| boy  | `#8dc5ff` | `#a8e6ff` |

---

## 11. Quick colour-family cheat sheet

| Surface | Family |
| ------- | ------ |
| Boy card V1 | dark brown + candle gold (`#f0c060`, `#fff3c4`) |
| Boy card V2 | pale blue + royal blue (`#6fa8ff`, `#4e74d6`) |
| Girl card V1 | cream + blush pink (`#e060a0`, `#9b4d76`) |
| Girl card V2 | near-black plum + hot pink (`#ff69b4`, `#ff7ba5`) |
| Anniversary lock | Taupe/charcoal · Maroon/gold · Tan/copper · Blush/red |
| Gift themes | Amber / Cocoa · Sky-Blue · Violet-Lilac · Coral · Mint-Teal · Forest · Charcoal — one per slot 1-4, differs by gift |
| Ending boy | Steel · Graphite · Gold · Emerald |
| Ending girl | Blush Rose · Lilac Dusk · Rose Gold Noir · Plum Midnight |
| Client builder | warm cream + boy/girl dual accent |
| Client auth / cards | violet `#8B5CF6` |
| Admin | cool grey-blue + indigo `#6366f1` |
| Landing | violet `#8B5CF6` on ink `#120d1c` |
