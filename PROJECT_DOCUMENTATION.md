# Birthday Card — Complete Project Documentation

## 1. Project Overview

This project is a Laravel-based birthday card application with three main parts:

1. Super Admin side
2. Client side
3. Public user-side birthday card experience

The system allows a super admin to manage clients, payments, and subscription settings. Clients can log in, manage their password, and create a personalized birthday card experience. End users can open the card via public URLs and view boy/girl themed birthday pages with gift sections and animated gift interactions.

---

## 2. Tech Stack

- Framework: Laravel 13
- PHP: 8.3+
- Frontend: Blade views + custom HTML/CSS/JS
- Styling: Inline CSS and Blade templates
- Images: Public image assets and remote placeholder images
- Mail: Laravel mail system with email templates
- Authentication: Laravel Auth
- Database: MySQL / Laravel default DB support

---

## 3. Main Project Structure

### Core folders

- app/Http/Controllers/Admin/ -> Super admin and client management logic
- app/Http/Controllers/Client/ -> Client authentication and profile logic
- app/Models/User.php -> User model with roles and subscription fields
- app/Mail/ -> Welcome email and password-reset email templates
- resources/views/admin/ -> Admin UI pages
- resources/views/client/ -> Client login, dashboard, profile, settings pages
- resources/views/birthday/ -> All public birthday card pages
- routes/web.php -> Main route definitions
- public/images/giftbox/ -> Gift box images used by birthday pages

---

## 4. Routes Overview

### 4.1 Home Route

- GET / -> welcome page

### 4.2 Super Admin Routes

Base prefix: /admin

- GET /admin/login -> admin login page
- POST /admin/login -> admin login process
- GET /admin/dashboard -> admin dashboard
- POST /admin/logout -> admin logout
- POST /admin/settings -> update default subscription fee
- GET /admin/clients -> list clients
- GET /admin/clients/create -> create client form
- POST /admin/clients -> store new client
- PATCH /admin/clients/{id}/toggle-status -> enable/disable client
- GET /admin/payments -> payment dashboard
- GET /admin/bg-owner -> BG owner page
- POST /admin/bg-owner/verify-pin -> verify BG owner PIN

### 4.3 Client Routes

Base prefix: /client

- GET /client/login -> client login page
- POST /client/login -> client login process
- GET /client/dashboard -> client dashboard
- GET /client/profile -> profile page
- GET /client/settings -> settings page
- POST /client/settings/password -> update password
- POST /client/logout -> logout
- GET /client/forgot-password -> forgot password page
- POST /client/forgot-password -> send reset link
- GET /client/reset-password/{token} -> reset password page
- POST /client/reset-password -> reset password

### 4.4 Birthday Card Public Routes

#### Normal pages

- GET /boy/page/{page}/{variant}
- GET /girl/page/{page}/{variant}

Example:

- /boy/page/1/1
- /boy/page/2/2
- /girl/page/3/1

#### Gift pages

- GET /boy/page/{page}/{variant}/gift/{gift}/{giftPage}
- GET /girl/page/{page}/{variant}/gift/{gift}/{giftPage}

Example:

- /boy/page/3/1/gift/1/1
- /girl/page/3/2/gift/2/3

These routes dynamically resolve to Blade views such as:

- birthday.boy-page-3
- birthday.girl-page-3-2
- birthday.boy-page-3-variant-1-gift-1-page-1

---

## 5. Controllers and Their Work

### 5.1 SuperAdminController

File: app/Http/Controllers/Admin/SuperAdminController.php

Responsibilities:

- Show admin dashboard
- Handle admin login and logout
- Restrict access using super_admin middleware
- Update default subscription fee
- Show BG owner page and verify PIN

### 5.2 ClientController

File: app/Http/Controllers/Admin/ClientController.php

Responsibilities:

- List all clients
- Show create client form
- Create new client accounts
- Generate random password automatically
- Assign client role and active status
- Send welcome email
- Toggle client status (active/disabled)

### 5.3 PaymentController

File: app/Http/Controllers/Admin/PaymentController.php

Responsibilities:

- Show payments summary
- Calculate total subscription fees
- Display client-related payment information

### 5.4 ClientAuthController

File: app/Http/Controllers/Client/ClientAuthController.php

Responsibilities:

- Login/logout for clients
- Show client dashboard, profile, settings
- Update client password
- Handle forgot-password flow
- Send password reset email
- Reset password through secure token flow

---

## 6. Super Admin Side — What Was Built

### Admin login system

- Admin login page exists at /admin/login
- Super admin access is restricted by middleware
- Only authorized super admins can reach admin dashboard

### Dashboard

- Dashboard UI is available in resources/views/admin/dashboard.blade.php
- Shows navigation, client summaries, and settings options

### Client management

- Admin can view, create, and manage clients
- On client creation:
  - random password is generated
  - client role is assigned
  - subscription start date is set
  - welcome email is sent

### Payment management

- Admin can view payment-related information
- Subscription fee totals are calculated

### BG owner feature

- There is a BG owner page and PIN verification flow
- This is managed through the super admin controller and admin views

### Subscription settings

- Admin can update the default subscription fee
- The value is stored on the user model and used for new clients

### Automation

- A console command app:disable-expired-clients disables clients whose subscription has expired after 30 days
- It is scheduled daily through bootstrap/app.php

---

## 7. Client Side — What Was Built

### Client login and registration flow

- Client can log in at /client/login
- Disabled accounts are blocked
- Password reset flow is available

### Client dashboard

File: resources/views/client/dashboard.blade.php

This is the main client page and contains a multi-step builder for the birthday card experience. The client can:

- choose a boy or girl theme
- set a lock code based on date of birth
- customize the welcome screen
- upload/select images
- configure gift sections
- preview summary
- generate a shareable card link

### Birthday card builder wizard — backend (Step 1 & Step 2 implemented)

A `birthday_cards` table now backs the client dashboard wizard so progress is saved to the database instead of being purely client-side/dummy JS.

Migration: `database/migrations/2026_07_15_100451_create_birthday_cards_table.php`

Columns:
- `user_id` — belongs to the client (`users` table)
- `theme` (`boy`/`girl`), `variant` (`1`/`2`) — Step 1
- `dob`, `lock_code` (4-digit PIN), `lock_hint` — Step 2
- `recipient_name`, `sender_name`, `welcome_message`, `profile_image_path` — reserved for Step 3
- `gifts` (JSON) — reserved for Step 4 gift sections
- `current_step`, `slug`, `is_published` — wizard progress + final share link tracking

Model: `app/Models/BirthdayCard.php` — casts `dob` to date, `gifts` to array, belongs to `User`. `User::birthdayCards()` hasMany relation added in `app/Models/User.php`.

Controller: `app/Http/Controllers/Client/BirthdayCardController.php`
- `currentDraft()` — fetches the client's latest non-published `BirthdayCard`, or creates a new blank one. This is the "draft in progress" record.
- `saveStep1(Request $request)` — validates `theme` (boy/girl) + `variant` (1/2), saves to the draft, bumps `current_step`. Returns JSON `{success, card_id}`.
- `saveStep2(Request $request)` — validates `theme`, `variant` (re-confirmed), `lock_code` (required, exactly 4 digits), `lock_hint` (nullable), `photo` (nullable image upload, max 5MB). Stores the photo in `storage/app/public/birthday-cards/profile/` (served via `public/storage` symlink — run `php artisan storage:link` if missing). Returns JSON `{success, card_id, profile_image_url}`. Note: `dob` column still exists on the table (reserved) but is no longer collected in the Step 2 UI — see below.

Routes (added in `routes/web.php`, inside the authenticated `client` prefix group):
- `POST /client/card/step1` → `client.card.step1`
- `POST /client/card/step2` → `client.card.step2`

`ClientAuthController::dashboard()` now loads the client's current draft `BirthdayCard` (if any) and passes it to the view as `$card`, so the wizard pre-fills previously saved theme/variant/PIN/hint/photo on page reload.

### Real-page preview support (`?photo=` query param)

To let the client dashboard show the **actual public birthday page** as a live preview (not a CSS recreation), the 4 page-1 templates now accept an optional `?photo=` query-string param that swaps the placeholder photo inside the arch-shaped `.arch-photo` frame for a real `<img>`:

- `resources/views/birthday/boy-page-1.blade.php` — was inline SVG only, now `@if(request('photo'))` renders `<img src="{{ request('photo') }}">` instead, falling back to the original SVG art when no `photo` param is present.
- `resources/views/birthday/girl-page-1-1.blade.php` — same pattern.
- `resources/views/birthday/girl-page-1-2.blade.php` — same pattern.
- `resources/views/birthday/boy-page-1-2.blade.php` — already used a real `<img>` (hardcoded Unsplash placeholder); now reads `request('photo', <unsplash-fallback-url>)` instead.

This does not change the public card experience for end users when no `photo` param is passed — it's purely additive and used by the dashboard's iframes.

### Dashboard wizard UI — Step 1 (Theme + Variant) — real design previews

- Client picks Boy or Girl theme (existing UI).
- After picking a theme, a "Choose Card Design" section appears below showing **2 real mini previews rendered via `<iframe>`** pointing at the actual public routes (`boy.page.variant` / `girl.page.variant` for page 1, variant 1 and 2) — not CSS gradient swatches. The iframe is scaled down (`transform: scale(0.25)` on a 400%-sized iframe) to fit a small thumbnail crisply:
  - Boy: "Midnight Gold" (`/boy/page/1/1`) and "Light Blue Sky" (`/boy/page/1/2`)
  - Girl: "Blush Petal" (`/girl/page/1/1`) and "Rose Bloom" (`/girl/page/1/2`)
- **Hover-to-enlarge**: hovering a preview box (desktop only, disabled under 768px since there's no hover on touch) opens a full-size overlay (`#variantZoomOverlay`) showing the same real page in a larger iframe so the client can inspect the design before choosing. Click the overlay backdrop to close.
- Selecting a variant highlights it; Continue is blocked with an inline error until both theme + variant are chosen.
- On Continue, an AJAX `fetch()` POST is sent to `client.card.step1` (no page reload). On success, the wizard advances to Step 2. On failure, an inline error is shown and the button re-enables.

### Dashboard wizard UI — Step 2 (Set PIN + Photo) — live real-page preview

- **No date-of-birth calendar field.** The client sets a **4-digit PIN directly** via 4 individual digit boxes (auto-advances focus per digit). A hint line above the PIN boxes reads: "Recommended: use the birthday person's date of birth as DD-MM — but you can set any 4 digits you like." There is no auto-fill/recommend button; it's just guidance text, and any 4 digits are accepted.
- A **live preview at the top of Step 2** (`#step2LivePreview`) is a full-width `<iframe>` pointing at the exact real page route the client picked in Step 1 (e.g. `/girl/page/1/2`), so what they see here is the literal end-user page, not a mockup.
- Uploading a photo (`onStep2ImageSelected`) creates a `blob:` object URL from the selected file client-side and reloads the iframe with `?photo=<blob-url>` appended — so the uploaded image appears **inside the real arch/mirror photo frame** of the actual template instantly, before saving. After a successful save, the blob URL is swapped for the permanent server `profile_image_url` and the blob is revoked (`URL.revokeObjectURL`) to avoid memory leaks.
- On Continue, both the Step 1 fields (theme/variant, re-sent for consistency) and Step 2 fields (`lock_code`, `lock_hint`, `photo`) are saved together via one `multipart/form-data` AJAX POST to `client.card.step2`.

### Mobile responsiveness

The dashboard wizard was built mobile-first-aware: the existing `@media (max-width: 768px)` and `@media (max-width: 480px)` blocks in `dashboard.blade.php` were extended to cover the new elements —
- `.variant-grid` (Step 1 design boxes) stacks to a single column under 768px.
- `.pin-box` shrinks progressively (48×58px desktop → 44×52px under 768px → 40×48px under 480px) so 4 boxes always fit without horizontal scroll on any phone.
- `.variant-zoom-box` (hover-enlarge overlay) becomes `94vw` wide on mobile.
- `.theme-cards` and `.welcome-layout` (pre-existing) already stacked correctly and were left as-is.

### Real-page iframe preview scaling (fixed a mobile "blink" + oversized preview bug)

The Step 1 design-preview thumbnails and the Step 2 live preview both embed the **actual public birthday page** in an `<iframe>` (see "Real-page preview support" above). Two mobile bugs were found and fixed:

1. **Oversized/overflowing preview on mobile.** The first version forced the iframe to `width:400%; transform: scale(0.25)` — a scale factor hardcoded for a specific container size. On mobile, where the thumbnail container is much narrower, this made the real page render oversized and overflow the screen. Fixed by rendering the iframe content at a **fixed 900px "desktop" width** (`.variant-thumb iframe`, `.live-page-preview iframe`) and using JS (`scaleVariantThumbs()` in the `<script>` block) to measure the *actual* container's `clientWidth` at runtime — on page load, on window resize, and right after a theme is selected (`requestAnimationFrame` after the container becomes visible) — then apply the correct `scale(container_width / 900)`. This makes both the Step 1 thumbnails and the Step 2 live preview always show the full desktop design shrunk to fit, consistently, on any screen size.
2. **Screen "blink" on mobile tap.** The hover-to-enlarge zoom (`openZoom`/`closeZoom`) was originally gated by `window.innerWidth <= 768`, but mobile browsers fire synthetic `mouseenter`/`mouseleave` events on tap for `:hover` compatibility — so tapping a design box on mobile briefly opened the full-page zoom overlay then immediately closed it, causing a visible flash. Fixed by replacing the width check with `IS_TOUCH_DEVICE = window.matchMedia('(hover: none), (pointer: coarse)').matches`, a reliable touch-capability check independent of viewport width (correctly handles resized desktop windows and tablets too). The zoom popup is now fully disabled on touch devices, not just narrow viewports.
3. **Whole dashboard page shifting left/right on mobile (horizontal scroll).** Even with `body { overflow-x: hidden }`, the fixed 900px-wide iframes could still influence the page's horizontal scroll extent on some mobile browsers before/around the point the JS scale was applied. Fixed by: adding `overflow-x: hidden; width: 100%` to `html` as well (not just `body`), adding `max-width: 100%` to `.variant-thumb` and `.live-page-preview`, and adding `contain: layout paint` to both — this CSS containment property tells the browser these boxes' internal content (including the oversized iframe) can never influence the layout size of anything outside them, which is the standards-based fix for this class of bug (more robust than relying on `overflow: hidden` alone).
4. **Screen "blink" on web/desktop when selecting a theme in Step 1.** All 4 variant-thumb iframes were originally given a real `src` on page load (loading all 4 real public pages immediately, even while their section was `display:none`). When `selectTheme()` toggled the relevant section to `display:block`, browsers had to repaint iframes that had been rendering invisibly in the background, causing a visible flash on desktop. Fixed by lazy-loading: the iframes now start with `data-src` only (no `src`), and `loadVariantThumbs(sectionId)` — called from `selectTheme()` — sets the real `src` from `data-src` the first time a theme's section is actually shown (and only once, guarded by `if (!iframe.src)`). A CSS opacity fade-in (`.variant-thumb iframe { opacity: 0; transition: opacity 0.25s }` → `.loaded { opacity: 1 }`, toggled via the iframe's native `load` event) smooths the moment the page finishes rendering inside, instead of a hard pop-in. This also improves initial dashboard load time since it no longer loads 4 full pages up front regardless of what the client picks.

### Step 2 layout — side-by-side on desktop/laptop, stacked on mobile

Step 2 ("Add Photo & Set PIN") now uses a responsive two-column grid (`.step2-grid`, `resources/views/client/dashboard.blade.php`):
- **Below 1000px width** (tablets/phones): single column — form fields, then the live preview, all full-width, stacked top-to-bottom (unchanged from before).
- **1000px and above** (laptop/desktop): two columns — the form (photo upload, PIN) on the left, and the **live preview as a sticky side panel** on the right (`.step2-preview-col { position: sticky; top: 1.5rem; }`) that stays in view while scrolling the form. This matches how Step 1's design felt on web and fixes the earlier version where the preview sat awkwardly full-width above the form on desktop.

### Mirror-shaped photo crop tool (Step 2)

Previously the uploaded photo was auto object-fit-cropped with no client control. Now, after selecting a photo, a **mirror-shaped crop box** appears (`#mirrorCropWrap` / `.mirror-crop-box`) matching the exact arch shape and aspect ratio (190:256) of the real template's `.arch-photo` frame:

- The photo is shown inside the arch-shaped box at a "cover" baseline scale (fills the frame with no gaps, like `object-fit: cover`).
- The client can **drag the photo** (mouse or touch — `setupMirrorDrag()` handles both `mousedown/mousemove/mouseup` and `touchstart/touchmove/touchend`) to reposition it left/right/up/down within the frame.
- A **zoom slider** (`#mirrorZoomSlider`, 100–250%) lets them zoom in for a tighter crop.
- On every drag/zoom change, `exportMirrorCrop()` renders the current crop to an off-screen `<canvas>` at the template's native 190×256 resolution and exports it as a JPEG `Blob` — this cropped image (not the original upload) is what gets shown in the Step 2 live preview iframe and what's ultimately uploaded to the server on Continue. What the client sees in the little mirror box is exactly what will appear on the real card.
- Field order in the Step 2 form was changed to put **Photo first, then PIN** (previously PIN was above the photo upload), per request.

### PIN field simplified to a single "DD-MM" input; hint field removed

Two further Step 2 refinements:
- The 4 separate PIN digit boxes (`.pin-box` × 4) were replaced with **one single text input** (`#pinDobInput`, class `.pin-dob-input`) with placeholder `DD-MM`. `onPinDobInput()` auto-inserts the `-` after 2 digits are typed as the client types (e.g. typing `1504` displays `15-04`). `getPinValue()` strips the dash before saving so the underlying `lock_code` sent to the server is still the plain 4-digit string (e.g. `1504`), keeping `BirthdayCardController::saveStep2()`'s `required|digits:4` validation unchanged. `setPinValue()` (used for pre-filling on reload) also re-inserts the dash for display.
- The **"Custom Hint Message (optional)"** field was removed entirely from the Step 2 UI, its pre-fill logic, and its AJAX payload. `BirthdayCardController::saveStep2()` no longer validates or writes `lock_hint` (the `lock_hint` DB column still exists on `birthday_cards` — it's just unused/always null now; no migration was run to drop it since that's a destructive change not requested).

Not yet built: Step 3 (welcome message + dedicated profile-image step — note Step 2 now already collects the main photo), Step 4 (gift sections), Step 5 (generate/share link) are still frontend-only placeholders with no backend persistence.

### Forgot-password fix — disabled-account popup + local mail delivery

The client "forgot password" flow (`GET/POST /client/forgot-password`, `ClientAuthController::forgotPassword()` / `sendResetLink()`) already sends a real, secure, token-based reset **link** by email (not OTP — confirmed with the user this is the intended approach, not a 6-digit code flow) via `App\Mail\ClientPasswordReset`. Two problems were found and fixed:

1. **Disabled accounts got a vague/wrong error, not a clear message.** The email lookup validation (`Rule::exists('users','email')->where(... status=active ...)`) silently failed for a disabled account's email, surfacing only as "invalid email" — never telling the client *why*. Fixed in `ClientAuthController::sendResetLink()`: the `Rule::exists` check was loosened to just `role = client` (so the email itself is still validated as belonging to a real client account), and a separate explicit check `if ($user->status !== 'active')` now returns a distinct error message: *"Your account is currently disabled. Please contact support to reactivate it before resetting your password."* On `resources/views/client/forgot-password.blade.php`, this specific error (detected via `str_contains($errors->first('email'), 'currently disabled')`) now renders as a **dedicated popup modal** ("🚫 Account Disabled") instead of the plain inline red error box used for other validation errors — matching the "popup ajaye" behavior requested. The existing token-based reset flow (`resetPasswordPage`/`resetPassword`) still correctly rejects disabled accounts too (its own `Rule::exists` still requires `status=active`), so a disabled user can never actually complete a reset even if they somehow had an old valid link.

2. **Email sending was silently failing in local dev because no SMTP catcher was running.** `.env` has `MAIL_MAILER=smtp`, `MAIL_HOST=127.0.0.1`/`localhost`, `MAIL_PORT=1025` — a standard local Mailpit/Mailhog setup — but nothing was listening on port 1025, so every `Mail::send()` call (welcome emails, password resets) was throwing and getting swallowed by the generic `catch (\Exception $e)` in `sendResetLink()`, showing "Unable to send reset link. Please try again." with no indication of the real cause. **Mailpit** (already installed as a binary at `/usr/local/bin/mailpit`) was started manually for this session: `mailpit --smtp 127.0.0.1:1025 --listen 127.0.0.1:8025`. Its web UI at `http://127.0.0.1:8025` lets you see every email the app sends without a real mailbox — useful for testing the welcome-email and password-reset flows going forward. **This was only started manually for this session and does not auto-start** — for it to work in future local dev sessions, either start it manually each time (`mailpit --smtp 127.0.0.1:1025 --listen 127.0.0.1:8025 &`) or set it up as a systemd/background service. In production, a real SMTP provider should be configured in `.env` instead.

### Profile and settings

- Profile page: resources/views/client/profile.blade.php
- Settings page: resources/views/client/settings.blade.php
- Client can update password securely

### Password reset

- Forgot password page exists
- Reset link is sent via email
- Token-based password reset flow is implemented

---

## 8. User Side / Public Birthday Card Pages

### 8.1 Purpose

These pages are the public birthday card experience shown to the final user. The user opens the card through a URL such as /boy/page/3/1 or /girl/page/3/2 and sees a themed page with gift boxes and interactive content.

### 8.2 Main behavior

The birthday pages are mostly static HTML/CSS/JS experiences with:

- custom full-screen layouts
- gift box animations
- interactive click events
- image-based greeting sections
- page variants for different color themes
- gift pages that reveal more content inside each gift

### 8.3 Variant system

The route logic uses two variants:

- Variant 1 = default theme
- Variant 2 = alternate color theme

Example:

- /boy/page/3/1 -> birthday.boy-page-3
- /boy/page/3/2 -> birthday.boy-page-3-2

### 8.4 Normal page routes (currently existing views)

#### Boy pages

- /boy/page/1/1 -> birthday.boy-page-1
- /boy/page/1/2 -> birthday.boy-page-1-2
- /boy/page/2/1 -> birthday.boy-page-2
- /boy/page/2/2 -> birthday.boy-page-2-2
- /boy/page/3/1 -> birthday.boy-page-3
- /boy/page/3/2 -> birthday.boy-page-3-2
- /boy/page/4/1 -> birthday.boy-page-4
- /boy/page/4/2 -> birthday.boy-page-4-2
- /boy/page/4/3 -> birthday.boy-page-4-3
- /boy/page/4/4 -> birthday.boy-page-4-4

#### Girl pages

- /girl/page/1/1 -> birthday.girl-page-1
- /girl/page/1/2 -> birthday.girl-page-1-2
- /girl/page/2/1 -> birthday.girl-page-2
- /girl/page/2/2 -> birthday.girl-page-2-2
- /girl/page/3/1 -> birthday.girl-page-3
- /girl/page/3/2 -> birthday.girl-page-3-2
- /girl/page/4/1 -> birthday.girl-page-4
- /girl/page/4/2 -> birthday.girl-page-4-2
- /girl/page/4/3 -> birthday.girl-page-4-3
- /girl/page/4/4 -> birthday.girl-page-4-4

### 8.5 Gift page routes (page 3, variants 1 and 2)

#### Boy gift routes

##### Variant 1

- /boy/page/3/1/gift/1/1 -> birthday.boy-page-3-variant-1-gift-1-page-1
- /boy/page/3/1/gift/1/2 -> birthday.boy-page-3-variant-1-gift-1-page-2
- /boy/page/3/1/gift/1/3 -> birthday.boy-page-3-variant-1-gift-1-page-3
- /boy/page/3/1/gift/1/4 -> birthday.boy-page-3-variant-1-gift-1-page-4
- /boy/page/3/1/gift/2/1 -> birthday.boy-page-3-variant-1-gift-2-page-1
- /boy/page/3/1/gift/2/2 -> birthday.boy-page-3-variant-1-gift-2-page-2
- /boy/page/3/1/gift/2/3 -> birthday.boy-page-3-variant-1-gift-2-page-3
- /boy/page/3/1/gift/2/4 -> birthday.boy-page-3-variant-1-gift-2-page-4
- /boy/page/3/1/gift/3/1 -> birthday.boy-page-3-variant-1-gift-3-page-1
- /boy/page/3/1/gift/3/2 -> birthday.boy-page-3-variant-1-gift-3-page-2
- /boy/page/3/1/gift/3/3 -> birthday.boy-page-3-variant-1-gift-3-page-3
- /boy/page/3/1/gift/3/4 -> birthday.boy-page-3-variant-1-gift-3-page-4

##### Variant 2

- /boy/page/3/2/gift/1/1 -> birthday.boy-page-3-variant-2-gift-1-page-1
- /boy/page/3/2/gift/1/2 -> birthday.boy-page-3-variant-2-gift-1-page-2
- /boy/page/3/2/gift/1/3 -> birthday.boy-page-3-variant-2-gift-1-page-3
- /boy/page/3/2/gift/1/4 -> birthday.boy-page-3-variant-2-gift-1-page-4
- /boy/page/3/2/gift/2/1 -> birthday.boy-page-3-variant-2-gift-2-page-1
- /boy/page/3/2/gift/2/2 -> birthday.boy-page-3-variant-2-gift-2-page-2
- /boy/page/3/2/gift/2/3 -> birthday.boy-page-3-variant-2-gift-2-page-3
- /boy/page/3/2/gift/2/4 -> birthday.boy-page-3-variant-2-gift-2-page-4
- /boy/page/3/2/gift/3/1 -> birthday.boy-page-3-variant-2-gift-3-page-1
- /boy/page/3/2/gift/3/2 -> birthday.boy-page-3-variant-2-gift-3-page-2
- /boy/page/3/2/gift/3/3 -> birthday.boy-page-3-variant-2-gift-3-page-3
- /boy/page/3/2/gift/3/4 -> birthday.boy-page-3-variant-2-gift-3-page-4

#### Girl gift routes

##### Variant 1

- /girl/page/3/1/gift/1/1 -> birthday.girl-page-3-variant-1-gift-1-page-1
- /girl/page/3/1/gift/1/2 -> birthday.girl-page-3-variant-1-gift-1-page-2
- /girl/page/3/1/gift/1/3 -> birthday.girl-page-3-variant-1-gift-1-page-3
- /girl/page/3/1/gift/1/4 -> birthday.girl-page-3-variant-1-gift-1-page-4
- /girl/page/3/1/gift/2/1 -> birthday.girl-page-3-variant-1-gift-2-page-1
- /girl/page/3/1/gift/2/2 -> birthday.girl-page-3-variant-1-gift-2-page-2
- /girl/page/3/1/gift/2/3 -> birthday.girl-page-3-variant-1-gift-2-page-3
- /girl/page/3/1/gift/2/4 -> birthday.girl-page-3-variant-1-gift-2-page-4
- /girl/page/3/1/gift/3/1 -> birthday.girl-page-3-variant-1-gift-3-page-1
- /girl/page/3/1/gift/3/2 -> birthday.girl-page-3-variant-1-gift-3-page-2
- /girl/page/3/1/gift/3/3 -> birthday.girl-page-3-variant-1-gift-3-page-3
- /girl/page/3/1/gift/3/4 -> birthday.girl-page-3-variant-1-gift-3-page-4

##### Variant 2

- /girl/page/3/2/gift/1/1 -> birthday.girl-page-3-variant-2-gift-1-page-1
- /girl/page/3/2/gift/1/2 -> birthday.girl-page-3-variant-2-gift-1-page-2
- /girl/page/3/2/gift/1/3 -> birthday.girl-page-3-variant-2-gift-1-page-3
- /girl/page/3/2/gift/1/4 -> birthday.girl-page-3-variant-2-gift-1-page-4
- /girl/page/3/2/gift/2/1 -> birthday.girl-page-3-variant-2-gift-2-page-1
- /girl/page/3/2/gift/2/2 -> birthday.girl-page-3-variant-2-gift-2-page-2
- /girl/page/3/2/gift/2/3 -> birthday.girl-page-3-variant-2-gift-2-page-3
- /girl/page/3/2/gift/2/4 -> birthday.girl-page-3-variant-2-gift-2-page-4
- /girl/page/3/2/gift/3/1 -> birthday.girl-page-3-variant-2-gift-3-page-1
- /girl/page/3/2/gift/3/2 -> birthday.girl-page-3-variant-2-gift-3-page-2
- /girl/page/3/2/gift/3/3 -> birthday.girl-page-3-variant-2-gift-3-page-3
- /girl/page/3/2/gift/3/4 -> birthday.girl-page-3-variant-2-gift-3-page-4

---

## 9. Images and Assets Used

### Public gift box images

The birthday pages use gift images stored in public/images/giftbox/:

- 1.png
- 2.png
- 3.png
- 4.png

These are used for the visible gift box visuals on page 3 and related card sections.

### Other image use

The birthday page views also use:

- remote placeholder images from picsum.photos
- remote profile images from unsplash
- inline SVG decorative patterns and backgrounds
- CSS-based gift box illustrations for interactive card sections

### Main idea

- Local images are used for gift boxes and reusable assets
- Remote images are used for demo photos and memory visuals
- CSS/HTML animations and SVGs create the interactive presentation

---

## 10. Email Features

The project also includes email functionality:

- Client welcome email after account creation
- Password reset email for clients

Email templates are stored in resources/views/emails/.

---

## 11. Summary

### Completed parts

- Super admin login and dashboard
- Client management and activation/deactivation
- Payments and subscription fee management
- Client login, settings, profile, and password reset
- Public birthday card pages for boy and girl themes
- Gift pages for multiple gift types and pages
- Gift box visuals and animated interactions
- Image and theme-based card experience

### Main value of the project

This is a complete birthday-card experience platform where:

- admins manage clients
- clients create and personalize the card experience
- end users receive a beautiful public card with gifts and memories
