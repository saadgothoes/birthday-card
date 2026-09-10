# Proposal Module

The proposal card is a third occasion, alongside birthday and anniversary — and
it is deliberately **not** shaped like either of them. A birthday or an
anniversary card is a five-screen story (lock → welcome → gift screen → three
gifts → ending). A proposal is **one page**: the recipient opens the link, the
reveal plays, the question arrives, and they answer it. There is no code to
enter, no gift to choose, and no ending screen — the answer *is* the ending.

Everything below is live: the four designs, their four colour themes each, the
four-step dashboard wizard, the six QR designs, and the public story at
`/c/{slug}`.

See also: [DASHBOARD_WIZARD_DOCUMENTATION.md §35](DASHBOARD_WIZARD_DOCUMENTATION.md)
for the dashboard and backend wiring, and [anniversary.md](anniversary.md) for
the occasion this one sits beside.

---

## Routes

| URL | View | Notes |
| --- | --- | --- |
| `/proposal/design/{design}/{theme}` | `birthday.proposal-design-{design}-theme-{theme}` | `design` 1-4 · `theme` 1-4. Anything outside those ranges is a 404. |
| `/c/{slug}` | the same view, with the client's words | The published card. `welcome` / `gifts` / `gift/{n}` / `ending` are **404** for a proposal — it has no inner pages. |

Route names: `proposal.design.theme`, and the existing `story.lock` for the
published card.

Unlike the boy/girl/anniversary convention, theme 1 **does** append its suffix
(`…-theme-1`, not a bare name). There is no "default" design here — a proposal
card always carries both numbers — so a special case for 1 would only be a
special case.

---

## Files

```
resources/views/birthday/
  proposal-design-{1..4}-theme-{1..4}.blade.php     16 thin wrappers
  partials/
    proposal-design-1.blade.php    Box & Ring Reveal
    proposal-design-2.blade.php    Locket / Heart Open
    proposal-design-3.blade.php    Countdown Reveal
    proposal-design-4.blade.php    Balloon Pop
    _proposal_tease.blade.php      the shared Yes / No module
    _proposal_demo.blade.php       plays a design's whole flow on a loop
    _proposal_ring.blade.php       the drawn ring, used when no photo is given
```

Each wrapper is one line — `@include('birthday.partials.proposal-design-N',
['proposalTheme' => T])` — exactly like the anniversary gift views. The design
lives once; the sixteen files are only addresses.

---

## The four designs

Every design is a complete page on its own: **it renders with zero query
parameters**, phone-first, with visible keyboard focus on both buttons, and it
honours `prefers-reduced-motion` (the sequence still runs, the particles do
not).

### Design 1 — Box & Ring Reveal
*Classic · warm-romantic.* One orchestrated sequence, and nothing in it starts
on its own — every step answers the tap before it.

```
closed box  →  ribbon unties and slides off (400ms)
            →  lid lifts on a left hinge (600ms)
            →  the folded letter rises and unfolds (scaleY .3→1, 500ms)
            →  the letter's lines fade in, one every 320ms
            →  the ring fades up at the letter's base, glowing
            →  the question and Yes / No
Yes         →  confetti + rose petals, the ring to hero size, "She Said YES!"
```

Two typefaces with clearly separate jobs: Cormorant Garamond for the question
and the letter, Inter for every piece of chrome.

### Design 2 — Locket / Heart Open
*Premium · heirloom.* The boldness is spent in exactly one place — the heart
splitting down its seam — and everything around it is kept quiet on purpose: a
slow breathing idle before the tap, and a celebration that is two portraits
drifting into one another rather than a firework.

Each half is the **same heart, clipped to its own side** (`inset(0 50% 0 0)` /
`inset(0 0 0 50%)`) and hinged on its outer edge, so the seam is exactly down
the middle and the two doors are guaranteed to match.

### Design 3 — Countdown Reveal
*Suspense · anticipation.* The countdown **is** the hero — there is no box,
locket or bouquet competing with it, so the withholding does the work. Faint
specks drifting upward are the only other motion. Each second the digit
**crossfades**; it never flips or reloads. At zero the digit bursts outward
(200ms), a radial wipe opens the reveal (500ms), and the question lands. The
payoff is sized to match: three staggered firework bursts, then the heading and
either the wedding-date card or the fallback line.

Space Grotesk for the numerals — the one place all-caps geometric numerals
genuinely fit — and Cormorant Garamond for the question once it is revealed.

It is also the only design that starts by itself. There is nothing on screen to
tap first, so the countdown begins ~1.1s after load.

### Design 4 — Balloon Pop
*Playful · light.* The one design that is not trying to be solemn, and it
commits to that front to back: a **single** rounded sans (Quicksand) with no
serif or script anywhere, and balloon colours drawn from one curated set per
theme rather than picked at random. The balloons pop 80ms apart, each with a
flash and a small confetti spray, then the ring drops in and bounce-settles.
The Yes sends a second, much larger wave of balloons up the whole screen. Its
sad-emoji set includes 🎈 alongside 😢.

The six strings are not eyeballed: each one's length and angle is worked out in
PHP from where its own balloon hangs, so they all converge on the same bow.

---

## The Yes / No module

`resources/views/birthday/partials/_proposal_tease.blade.php`. All four designs
`@include` it and call `initTeaseButtons({root, onYes})` — the joke is written
once, not four times.

- Pointer over **No** (or a tap on it) moves the button somewhere else inside
  the row and shrinks it a little each time, puffing a sad emoji out of where
  it was.
- Its label runs through *"No" → "Are you sure?" → … → "Just say yes 🥹"*, so
  the joke reads even to someone who never catches it, and an optional
  `[data-tease-stage]` line adds running commentary.
- **Yes** always answers, and grows as No shrinks.

**Accessibility.** The dodge is bound to *pointer* events only. A keyboard user
tabbing to No is never teleported off the control they are focused on — that is
a trap, not a joke. Pressing it plays the same emoji puff, advances the label,
and returns focus to Yes, which is always one Tab away. Both buttons carry a
visible focus ring. Under `prefers-reduced-motion` the button still moves, but
instantly and without the emoji shower.

The module is skinned by whichever theme is on the page, through the custom
properties each design defines: `--pt-yes-bg`, `--pt-yes-ink`, `--pt-no-bg`,
`--pt-no-ink`, `--pt-ink`, `--pt-ring`.

A design can override the emoji set, the label ladder and the commentary
(`emojis`, `labels`, `stages`) without touching the logic — that is how Balloon
Pop gets its own tone.

---

## `?demo=1` — the design playing itself

`resources/views/birthday/partials/_proposal_demo.blade.php`. Inert unless the
page is asked for with `?demo=1`, in which case it drives the page through its
own whole flow, on a loop:

```
idle (1.1s, so the invitation can be read)
→ the tap
→ the design's opening motion
→ what it reveals
→ the question
→ the No button is nudged twice and runs away
→ Yes
→ the celebration, held 4.2s
→ back to the start
```

**It is not a video file.** It is the real page driving itself — the same code
path a real tap triggers, played by a script instead of a finger. So a preview
can never drift out of step with the design it is previewing, and there is
nothing to re-render when a design changes. The No button is made to run by
dispatching a real `pointerenter`, so the tease module's own dodge does the
work rather than a second copy of it.

Each design registers its hooks before including the partial:

```js
window.__proposalDemo = {
    phases: [0, 760, 2400],   // ms after open() when beats 1, 2 and 3 land
    open:  fn,                // what a tap does
    yes:   fn,                // what pressing Yes does
    reset: fn,                // put it back to the very beginning
};
```

Everything after the tap runs off those `phases`, because the design already
knows how long its own sequence takes — the captions and the moment the No
button starts running are the same clock, so they cannot drift apart. Design 3
is the one that normally starts itself; under `?demo=1` it waits for the loop to
start it instead, so the two never run one countdown between them.

The five beats it reports (0-4) are the five in `PROPOSAL_DESIGNS[n]['beats']`.
Each is posted to the parent window as `{proposalBeat: n}` and written to
`<html data-pd-beat>` — the first is what lights up the dashboard's chips, the
second is what makes the loop testable.

A demo that is scrolled off screen or in a hidden tab stops itself
(`IntersectionObserver` + `visibilitychange`), so four of them are not animating
for nobody.

---

## Themes

**Four per design — sixteen in all.** Two of every design's four are the *soft*
side and two the *bold* side; that is the only grouping the dashboard shows, and
it is what "2 for her, 2 for him" means in the picker.

Which palette is theme 1 differs per design, because theme 1 is that design's
own signature look rather than a shared slot.

### Design 1 — Box & Ring Reveal

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Rose Gold & Cream | soft | `#f7ece2 → #e8c9b0` | `#a35a56` |
| 2 | Blush Pearl | soft | `#fdf2f5 → #f3d6e0` | `#c2607f` |
| 3 | Midnight Velvet | bold | `#2f3a63 → #161b31` | `#d9b26a` |
| 4 | Emerald & Gold | bold | `#1e5c4d → #0e332c` | `#e2b866` |

### Design 2 — Locket / Heart Open

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Burgundy & Gold | bold | `#a35a56 → #5c1420` | `#c9a75c` |
| 2 | Rose Quartz | soft | `#f6dce4 → #dba9bd` | `#a4485f` |
| 3 | Champagne Ivory | soft | `#f7efe3 → #e2cdae` | `#9c7247` |
| 4 | Onyx & Silver | bold | `#33393f → #14171b` | `#cfd6dd` |

### Design 3 — Countdown Reveal

All four stay dark; a light countdown would give the withholding away. 1 & 2 are
the cool pair, 3 & 4 the warm-lit pair.

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Midnight Violet | bold | `#3a1f3d → #1b1330 → #0d0918` | `#f0d08a` |
| 2 | Deep Sea | bold | `#0d3b4d → #07202e → #03121b` | `#7fe3d4` |
| 3 | Starlit Rose | soft | `#5c2a44 → #2a1526 → #150a13` | `#ffc2d4` |
| 4 | Aurora Ice | soft | `#2b3566 → #151a33 → #080b1a` | `#bcd6ff` |

### Design 4 — Balloon Pop

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Pastel Sky | soft | `#cfe8f0 → #f6d9e3` | `#e2698c` |
| 2 | Candy Blush | soft | `#ffeef4 → #ffd9c7` | `#ef6f8e` |
| 3 | Mint & Sunshine | bold | `#d8f3e6 → #fdf3cf` | `#2f9e7a` |
| 4 | Bold Pop | bold | `#dbe7ff → #ffe2e2` | `#2f5fe0` |

---

## Request params

Every design accepts the shared set, so the dashboard can offer one "closing
message" field across all four. Only the fields a design actually reads are
stored for it (see §35) — a Balloon Pop card carries no countdown length, and a
Countdown card carries no letter.

Fallbacks are not written into the pages. They live once, in
`PROPOSAL_DESIGNS[n]['defaults']`, and are read from there both by the page and
by the wizard's pre-fill — so what a client sees before typing anything is
exactly what an untouched card would send.

### Shared by all four

| Param | Meaning | Fallback |
| --- | --- | --- |
| `to_name` | who it is for | Ayesha |
| `from_name` | who it is from | Bilal |
| `question` | the question | Will you marry me? |
| `yes_label`, `no_label` | the two buttons | Yes 💍 / No |
| `yes_heading` | the celebration heading | She Said YES! 💍 |
| `closing_line` | the line under it | *(per design)* |
| `signed` | the signature | — always yours |
| `ring_photo` | a photo of the ring | the drawn ring |
| `theme` | overrides `proposalTheme` when the partial is included without one | 1 |
| `preview_stage` | skip ahead — see below | — |
| `demo` | `1` plays the whole flow on a loop — see above | — |

### Per design

| Design | Extra params |
| --- | --- |
| 1 Box & Ring | `heading` · `tap_label` · `letter_text` (one line per newline, max 8) |
| 2 Locket | `heading` · `tap_label` · `couple_photo` (drawn silhouette fallback) |
| 3 Countdown | `pre_label` · `countdown_seconds` (1-10, default 5) · `wedding_date` · `altar_label` · `fallback_line` |
| 4 Balloon Pop | `heading` · `tap_label` |

### `preview_stage`

The deep link the dashboard's live preview uses, so a client reads their own
words without tapping through the reveal every keystroke.

| Value | Effect |
| --- | --- |
| `open` | designs 1, 2, 4 — the box open / locket open / balloons popped, with the question showing |
| `reveal` | design 3 — skip the countdown straight to the reveal |
| `yes` | any design — play the celebration |

---

## Dashboard

A four-step wizard of its own, `#proposalFlow` / `.prop-panel` /
`.prop-nav-item`, shown under `body.occasion-proposal`. The birthday and
anniversary flows are untouched.

| # | Panel | Saves | Live preview |
| - | --- | --- | --- |
| 1 | `#propPanelDesign` | `variant` (design) + `gift_screen_variant` (theme) | the design cards' motion loops, then `/proposal/design/{d}/{t}` |
| 2 | `#propPanelContent` | `gift1_data` | `…?{every field}&preview_stage=open` |
| 3 | `#propPanelMusic` | `music_data` (via the shared `saveStep9`) | the shared clip picker |
| 4 | `#propPanelQr` | `qr_data` + `slug`, `is_published` (via the shared `saveStep10`) | the generated link + QR |

**Step 1** shows the four designs as cards, each with its mood, a one-paragraph
summary, and **the design itself, running its whole flow on a loop** — an iframe
on `?demo=1` (see above), so the client watches the box open, the letter unfold,
the question arrive, the No button run away and the celebration fire, before
choosing. The five beat chips under each card light up as its preview reaches
them, so what is happening is also named. A CSS still holds the space until the
page has loaded, and a real clip dropped at
`public/videos/proposal/design{n}.mp4` is played over the top when one exists.

The previews are loaded on first sight of this step rather than with the
dashboard, because a birthday card never opens this panel. Picking a design
reveals **its own** four themes as swatches (labelled *Soft* / *Bold*) and points
the full-size preview below them at the chosen design, also playing through.

**Step 2** renders only the fields the chosen design reads — a design's unused
fields are not disabled, they are not there, and photo slots follow the same
rule. **Every empty box is pre-filled with that design's sample wording**, from
the one copy in `PROPOSAL_DESIGNS[n]['defaults']` that the page itself falls back
to, so the client starts from a complete, sensible card and edits what they want
to change rather than writing one from nothing. Anything already typed or already
saved is left alone.

Three buttons decide what the live preview holds:

| Mode | Shows |
| --- | --- |
| **Your words** *(default)* | parked at the question, so the client reads what they typed without tapping through the reveal on every keystroke |
| **After the Yes** | the celebration — the half of the page the other modes never reach |
| **▶ Play it through** | the whole thing on a loop, with *their* words in it (`?demo=1`) |

Steps 3 and 4 are the *same* endpoints and the *same* clip-picker element the
other two occasions use; `openClipPicker(url, 'propMusicClipMount')` moves the
one picker into this step.

Resume lands on `min(4, current_step)`.

### QR designs

Proposal cards get **six of their own**, `QR_THEMES['proposal']`, resolved by
occasion (`qrThemesForCard()`) rather than by `theme` — a proposal card has no
boy/girl `theme` at all.

| # | Design | Look |
| - | --- | --- |
| 1 | Rose Gold Vow | Rounded modules on warm cream, petal corners |
| 2 | Midnight Velvet | Dark card, gold dots, sparkle corners |
| 3 | Burgundy Seal | Deep red squares, gold double border, hearts |
| 4 | Blush Petal | Soft dots on blush, petal corners, no border |
| 5 | Emerald Band | Rounded emerald modules on ivory, gold rule |
| 6 | Bold Pop | Bright squares, dashed frame, nothing else |

---

## The public story

`PublicStoryController` renders an `occasion === 'proposal'` card at `/c/{slug}`
through the same "templates read their own params" route everything else uses.

- `isProposal()` joins `isAnniversary()` in `isLive()`.
- `lock()` — the route the QR encodes — **is** the proposal. There is no code to
  enter, so it renders the design straight away with the client's words.
- `guard()` 404s the inner pages for a proposal, rather than bouncing them back
  to a lock screen that does not exist.
- `proposalView()` builds `birthday.proposal-design-{variant}-theme-{gift_screen_variant}`
  — the same two numbers the dashboard's preview URL is built from, so the page
  a client approved and the page a recipient opens are the same file.
- `proposalParams()` hands over whatever was stored rather than a fixed list,
  and resolves the photo paths to URLs.
- `StoryChrome::proposal()` adds **only** the music control: no Next, no Back,
  and no curtain — the design already ends on its own celebration, and there is
  nowhere else to go.
- The shell gets `side=proposal` (a badge palette in `story/shell.blade.php`) and
  the tab title *"A Question For You"*.

Music needed nothing new: `musicClip()` reads `music_data` without caring about
the occasion.

---

## Storage

No migration. A proposal reuses the generic columns:

| Column | Holds |
| --- | --- |
| `occasion` | `'proposal'` |
| `variant` | the design, 1-4 |
| `gift_screen_variant` | that design's colour theme, 1-4 |
| `gift1_data` | every word and photo on the page (see below) |
| `music_data`, `qr_data`, `slug`, `is_published` | the shared music + QR steps |
| `current_step` | 1-3 through the proposal's own steps; the shared music/QR steps still write 9 and 10, which the wizard clamps to 4 |

`gift1_data` shape:

```json
{
  "design": 1,
  "theme": 3,
  "to_name": "Sara",
  "from_name": "Umair",
  "photos": { "ring_photo": "birthday-cards/proposal/….png" },
  "heading": "For you",
  "tap_label": "Tap to open",
  "letter_text": "line\nline",
  "question": "Will you marry me?",
  "yes_label": "Yes", "no_label": "No",
  "yes_heading": "She Said YES!",
  "closing_line": "…", "signed": "— always yours"
}
```

Uploads land in `storage/app/public/birthday-cards/proposal`.

---

## Design-safe limits

Every design is a fixed layout, so each field carries the length its own slot can
actually show (`BirthdayCardController::PROPOSAL_LIMITS`), enforced on the way in
rather than trimmed on the way out.

| Field | Max |
| --- | --- |
| `to_name`, `from_name` | 24 |
| `heading`, `tap_label` | 32 |
| `question` | 60 |
| `yes_label`, `no_label` | 20 |
| `yes_heading` | 44 |
| `pre_label`, `fallback_line` | 60 |
| `altar_label` | 40 |
| `closing_line` | 160 |
| `signed` | 30 |
| `letter_text` | 400 characters **and** 8 lines |
| `countdown_seconds` | integer 1-10 |

---

## Preview sizing

Every preview — the four design cards and both full-size boxes — renders the
real page at 900px wide and shrinks it with `transform: scale()` to whatever
width its box actually has. Three things keep that honest:

- A `ResizeObserver` watches the boxes, so the fit follows the box whoever
  changed it (revealing a panel, collapsing the sidebar, rotating a phone, a
  late web font). It schedules one coalesced sweep per frame rather than fitting
  individual entries — see
  [DASHBOARD_WIZARD_DOCUMENTATION.md §35.4.2](DASHBOARD_WIZARD_DOCUMENTATION.md)
  for why that distinction is load-bearing.
- `goToPropStep()` fits synchronously as it reveals a panel, so nothing paints
  cropped for a frame first.
- The proposal boxes are **5:4**, not the 16:10 the rest of the dashboard uses.
  A proposal is a tall page — hero, question, two buttons, a closing line — and
  at 16:10 the bottom of every design was cut off.

---

## Known limitations

- **The slug stem.** Every card is given its share slug when the wizard page
  first loads (so the QR previews have a settled address to encode) — which is
  before the occasion is picked. A proposal card therefore usually carries a
  `birthday-…` stem. This is existing behaviour shared with anniversary cards;
  the slug is only an address and nothing reads meaning from it.
- **No lock screen.** By design: a proposal is opened in person, in the moment.
  If one is ever wanted, the `story.lock` / `story.unlock` pair is still there —
  it is only skipped for this occasion.
- The dashboard's four previews are the live pages on `?demo=1`, not video
  files. A real clip can still be dropped in
  `public/videos/proposal/design{1..4}.mp4` and is played over the top
  automatically if one is ever wanted.
