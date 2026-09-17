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
    proposal-design-1.blade.php    The Last Message
    proposal-design-2.blade.php    Scratch the Foil
    proposal-design-3.blade.php    Written in the Stars
    proposal-design-4.blade.php    The Roll
    _proposal_tease.blade.php      the shared question + Yes / No module
    _proposal_after.blade.php      the letter the Yes opens, and its four entrances
    _proposal_fx.blade.php         the shared particle layer
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
honours `prefers-reduced-motion` — under which the page still moves through
every state, it just arrives at each one rather than travelling there.

The brief they share: a proposal is read by someone in their twenties, usually
standing up, usually with someone watching their face. Anything that performs
too hard reads as a greeting card, so each of these earns its moment a
different way — one is written in the language the couple already uses, one is
worked for with a finger, one withholds, and one is made of their own
photographs.

### Design 1 — The Last Message
*Modern · the way you actually talk.* A chat thread that types itself out. It
is the one that does not perform: no box, no ornament, just the words in the
shape they would really arrive in.

```
a thread with one unread  →  tap
→  typing…                 (a real pause, not a loading state)
→  the messages land, one at a time, each after its own typing
→  the last one is the question, in a bubble of its own
→  the reply row slides up where the keyboard would be
Yes  →  the answer is sent as a bubble and read, hearts burst out of it,
        then they are typing again — and the letter arrives
```

The typing pause before each message is the whole trick: it is the only part of
the page that makes a reader wait, and waiting for a message is a feeling they
already have. Two faces with separate jobs — Inter for the thread, Instrument
Serif for the one bubble that is the question.

Themes 3 and 4 are dark, because a chat thread is one of the few places a dark
theme is the *expected* one.

### Design 2 — Scratch the Foil
*Tactile · they make it happen.* A gold foil card that does not move until they
move it. The question is something they uncover, at whatever speed they choose,
rather than something they are shown.

The foil is a real canvas with `destination-out` under the finger, not an image
fading out — the torn edge follows exactly where they went. How much is gone is
**measured off the pixels** (every tenth, for speed), not guessed from the
number of strokes, and past 48% the card gives up the rest of itself at once so
nobody has to scrub the corners. Each stroke throws a little metal dust off the
canvas through the shared particle layer.

The shimmer sweeping across the metal stops the moment they touch it: after
that the torn edge is the interesting part.

### Design 3 — Written in the Stars
*Cinematic · quiet and huge.* A night sky, seven stars brighter than the rest,
and no interface at all. It is the design that trusts the question to be
enough — the one to choose when anything sweeter would be too much.

The constellation is one SVG drawn with `stroke-dashoffset`, so each segment is
*drawn* rather than faded in, 340ms apart. The band is an **arc** between each
pair of stars and the stone a shallow triangle over the one gap in it: joining
seven points with straight lines gives a kite every time, however the points
are moved — the curve is what makes it read as a ring.

The sky behind it is its own canvas — ~190 stars that drift and twinkle on a
single rAF loop, paused when the tab is hidden.

### Design 4 — The Roll
*Memory · your photos, your story.* A stack of polaroids, each captioned, that
the recipient flicks away one at a time. The last frame is blank and **develops
in front of them** into the ring and the question.

This is the one design that is different for every couple who sends it, and the
only one that ends where it started: on the Yes, every photograph that was
thrown away comes back, scattered across the screen, and then the frame they are
holding is turned over — the letter is written on the back of it.

The flick is a real drag — the card follows the finger, rotates with the
distance, and is thrown when it is let go past 74px; under that it springs
back, which is what makes the threshold discoverable without a word of
instruction. A tap counts as a flick too.

A card with no photo uploaded for it is not an empty box: it falls back to a
duotone in the theme's own colours, so a card sent with no photos at all still
looks deliberate. Quicksand is gone; captions are Caveat, the question is
Instrument Serif.

---

## The question and the two buttons

`resources/views/birthday/partials/_proposal_tease.blade.php`. All four designs
`@include` it and call `initTeaseButtons({root, onYes})` — the joke is written
once, not four times.

- Pointer over **No** (or a tap on it) springs the button somewhere else inside
  the row and shrinks it a little each time, puffing an emoji out of where it
  was.
- Its label runs down a ladder — *"No" → "are you sure" → … → "ok fine"* — so
  the joke reads even to someone who never catches the button.
- After **five dodges it gives up**: it shrinks out of existence and the Yes
  takes the whole row. A gag with no ending is just an obstacle, and an endless
  chase on a page like this one starts to feel mean — so the page makes the
  decision the moment the joke stops being funny.
- A short `navigator.vibrate` on each dodge and on the Yes, where the device
  has it.

**Accessibility.** The dodge is bound to *pointer* events only. A keyboard user
tabbing to No is never teleported off the control they are focused on — that is
a trap, not a joke. Pressing it plays the same puff, advances the ladder,
reaches the same ending, and returns focus to Yes, which is always one Tab
away. Both buttons carry a visible focus ring.

Designs 2 and 4 also carry a plain **"Reveal it instead" / "Skip to the last
one"** button: a canvas you have to drag and a stack you have to swipe are not
interfaces on their own, and that button is the way through for a keyboard, a
screen reader, or anyone who would rather not.

The module is skinned by whichever theme is on the page, through the custom
properties each design defines: `--pt-yes-bg`, `--pt-yes-ink`, `--pt-no-bg`,
`--pt-no-ink`, `--pt-ink`, `--pt-ring`. A design can override the emoji set,
the label ladder, the commentary and `giveUpAt` without touching the logic.

---

## What the Yes opens

`resources/views/birthday/partials/_proposal_after.blade.php`. The Yes used to
land on a heading and one line of text, which made the best moment on the page
the least designed one — and the least *surprising*. It now opens **a letter**:
the thing they will read twice, written out line by line, sealed with both names
and the date it happened.

The sheet is shared, so all four designs end on something equally finished. What
is **not** shared is how it arrives — each design hands over its own entrance,
because the surprise has to come out of the thing they were just looking at:

| Design | `afterEnter` | The surprise |
| --- | --- | --- |
| 1 The Last Message | `chat` | They are *typing again* — the answer was not the end of the conversation — and the letter swells up out of the last message |
| 2 Scratch the Foil | `flip` | The card turns over. The letter was on the back of it the whole time |
| 3 Written in the Stars | `sky` | A meteor shower first, and the letter resolves out of the sky the way the constellation did |
| 4 The Roll | `photo` | The whole roll flies back, then the frame they are holding is turned over — people write on the back of photographs |

```html
@include('birthday.partials._proposal_after', ['afterEnter' => 'flip'])
```
```js
showAfter({ fx: 'hearts' });   // 'confetti' | 'hearts' | 'petals' | 'stars' | 'meteors'
hideAfter();                   // the looping demo, starting over
```

The sheet lands first and the letter writes itself after it, one line every
190ms, with the closing line and then the seal (ring, both names, signature,
date) timed off the number of lines rather than a fixed delay — so a
three-line letter does not sit waiting for a six-line one's clock.

The letter body is set **left-aligned** while everything around it is centred:
at this width the lines wrap, and centred wrapped lines fray into a diamond.

Skinned through `--pk-scrim`, `--pk-bg`, `--pk-ink`, `--pk-soft`, `--pk-accent`,
`--pk-line`, `--pk-display` and `--pk-letter`.

---

## The particle layer

`resources/views/birthday/partials/_proposal_fx.blade.php`. One canvas, one rAF
loop, shared by all four. Before it existed every design carried its own
confetti — four copies of the same maths, each drifting a little from the
others.

It is a *flavour* API rather than a particle API: a design asks for the thing it
means and the engine owns how that looks, so two designs asking for confetti get
the same confetti.

```js
pfx.burst('confetti', { x, y, count, colors, power, spread });
pfx.rain('hearts',    { count, colors, duration });
pfx.meteors({ count });
pfx.clear();
```

Flavours: `confetti`, `hearts`, `petals`, `dust`, `stars`, plus `meteors`.
Colours default to the page's own `--fx-colors` list, so a design that has set
its theme can ask for a burst with no arguments at all. The loop runs only while
there are particles and stops itself when the tab is hidden; under
`prefers-reduced-motion` nothing is drawn at all.

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
→ the Yes, and the letter it opens, held 6.2s
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
knows how long its own sequence takes — and each design *computes* them rather
than hardcoding them (`questionAt()` is one message count, one segment count,
one card count away from the truth), so adding a line to the sample wording
cannot put the captions out of step with the page.

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

### Design 1 — The Last Message

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Paper | soft | `#f7f3ec → #e6ded1` | `#b5654a` |
| 2 | Bubblegum | soft | `#fff1f6 → #ffd9e6` | `#e0507f` |
| 3 | Night Mode | bold | `#1b1c22 → #0b0c10` | `#8b9cff` |
| 4 | Matcha | bold | `#1c3a31 → #0d1f1a` | `#9fe0b4` |

### Design 2 — Scratch the Foil

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Gold on Cream | soft | `#fbf5ea → #ead9bd` | `#a8813c` |
| 2 | Rose Foil | soft | `#fff4f5 → #f3d3d9` | `#c06078` |
| 3 | Holo Black | bold | `#1a1b21 → #0a0b0e` | `#9ad7ff` |
| 4 | Emerald Foil | bold | `#17493d → #0a241e` | `#d8b262` |

Each theme also carries the three stops of the metal itself (`foil1`-`foil3`),
which is what the canvas paints and what the dust is coloured with.

### Design 3 — Written in the Stars

All four stay dark; a lit sky is not a sky. They differ in what colour the dark
is, and what the stars are made of.

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Deep Indigo | bold | `#1b2450 → #101733 → #05070f` | `#ffe9a8` |
| 2 | Nebula Rose | soft | `#43184a → #2a1030 → #0d060f` | `#ffbcd6` |
| 3 | Aurora | soft | `#0d3a48 → #08202a → #030b10` | `#8ff0de` |
| 4 | Obsidian | bold | `#1a1a1e → #0d0d10 → #000000` | `#e8e6e1` |

### Design 4 — The Roll

| # | Name | Side | bg | accent |
| - | --- | --- | --- | --- |
| 1 | Film Cream | soft | `#f6efe3 → #e4d8c6` | `#c0654e` |
| 2 | Sunwash | soft | `#fff3e6 → #ffd9c0` | `#e57a52` |
| 3 | Darkroom | bold | `#202124 → #0e0f11` | `#f0c05a` |
| 4 | Cobalt | bold | `#1e3c70 → #101f3c` | `#ffd66b` |

---

## Request params

Every design accepts the shared set, so the dashboard can offer one "closing
message" field across all four. Only the fields a design actually reads are
stored for it (see §35) — a Scratch card carries no captions, and a Roll carries
no chat thread.

Fallbacks are not written into the pages. They live once, in
`PROPOSAL_DESIGNS[n]['defaults']`, and are read from there both by the page and
by the wizard's pre-fill — so what a client sees before typing anything is
exactly what an untouched card would send.

### Shared by all four

| Param | Meaning | Fallback |
| --- | --- | --- |
| `to_name` | who it is for | Ayesha |
| `from_name` | who it is from | Bilal |
| `heading` | the line at the top | *(per design)* |
| `tap_label` | what the page asks them to do | *(per design)* |
| `question` | the question | Will you marry me? |
| `yes_label`, `no_label` | the two buttons | Yes 💍 / No |
| `yes_heading` | the heading on the letter the Yes opens | *(per design)* |
| `letter_text` | that letter, one line per line, max 6 | *(per design)* |
| `closing_line` | the line under it | *(per design)* |
| `signed` | the signature | — always yours |
| `ring_photo` | a photo of the ring — the seal on the letter too | the drawn ring |
| `theme` | overrides `proposalTheme` when the partial is included without one | 1 |
| `preview_stage` | skip ahead — see below | — |
| `demo` | `1` plays the whole flow on a loop — see above | — |

### Per design

| Design | Extra params |
| --- | --- |
| 1 The Last Message | `chat_text` (one message per line, max 5) · `couple_photo` (the thread's avatar; falls back to the sender's initial) |
| 2 Scratch the Foil | — (the shared set is all it reads) |
| 3 Written in the Stars | — (the shared set is all it reads) |
| 4 The Roll | `caption_text` (one caption per line, max 4) · `photo_1`, `photo_2`, `photo_3` |

### `preview_stage`

The deep link the dashboard's live preview uses, so a client reads their own
words without tapping through the reveal every keystroke.

| Value | Effect |
| --- | --- |
| `open` / `reveal` | every design — the thread filled in / the foil off / the constellation joined / the roll gone through, with the question showing |
| `yes` | any design — play the celebration and open the letter |

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
on `?demo=1` (see above), so the client watches the thread type itself out, the
foil come off, the stars join, the No button run away and the celebration fire,
before choosing. The five beat chips under each card light up as its preview
reaches them, so what is happening is also named. A CSS still holds the space
until the page has loaded — one loop per design, in that design's theme-1
colours — and a real clip dropped at `public/videos/proposal/design{n}.mp4` is
played over the top when one exists.

The previews are loaded on first sight of this step rather than with the
dashboard, because a birthday card never opens this panel. Picking a design
reveals **its own** four themes as swatches (labelled *Soft* / *Bold*) and points
the full-size preview below them at the chosen design, also playing through.

**Step 2** renders only the fields the chosen design reads — a design's unused
fields are not disabled, they are not there, and photo slots follow the same
rule (The Roll shows four slots, The Last Message two, the other two one).
**Every empty box is pre-filled with that design's sample wording**, from the
one copy in `PROPOSAL_DESIGNS[n]['defaults']` that the page itself falls back
to, so the client starts from a complete, sensible card and edits what they want
to change rather than writing one from nothing. Anything already typed or already
saved is left alone.

Three buttons decide what the live preview holds:

| Mode | Shows |
| --- | --- |
| **Your words** *(default)* | parked at the question, so the client reads what they typed without tapping through the reveal on every keystroke |
| **After the Yes** | the letter the Yes opens — the half of the page the other modes never reach |
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
  "photos": { "couple_photo": "birthday-cards/proposal/….png" },
  "heading": "Us",
  "tap_label": "Tap to open",
  "chat_text": "line\nline",
  "question": "Will you marry me?",
  "yes_label": "Yes", "no_label": "No",
  "yes_heading": "she said yes 🥹",
  "letter_text": "line\nline",
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
| `closing_line` | 160 |
| `signed` | 30 |
| `chat_text` | 300 characters **and** 5 lines |
| `caption_text` | 200 characters **and** 4 lines |
| `letter_text` | 420 characters **and** 6 lines |

The three multi-line fields are in `PROPOSAL_MULTILINE`, which is read three
times: the page splits on it, the wizard's textarea enforces it through
`data-max-lines`, and `saveProposalContent` validates against it. One line in is
one object on the page — a bubble, a photograph — so the cap is a count of
things, not a guess at a height.

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
- **Cards saved under the previous four designs** keep their `gift1_data`. Every
  field the new designs share (names, question, buttons, closing line,
  signature) still renders, and a `letter_text` saved for the old Design 1 is
  read straight back in as the letter the Yes opens — same key, better home. The
  fields only the old designs had (`countdown_seconds`, `wedding_date`,
  `pre_label`, `altar_label`, `fallback_line`) are ignored by the page and
  dropped the next time the card is saved. Nothing 500s, and nothing needed a
  migration.
- The dashboard's four previews are the live pages on `?demo=1`, not video
  files. A real clip can still be dropped in
  `public/videos/proposal/design{1..4}.mp4` and is played over the top
  automatically if one is ever wanted.
