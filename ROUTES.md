# Routes Documentation

Yeh file project ke tamam registered routes ki complete list hai — Admin side, Client side, aur Birthday Card (public) side. Har route ke sath uska **HTTP Method**, **URL**, **Name**, **Controller/Action**, **Middleware**, aur **use-case scenario** likha gaya hai.

Source: `routes/web.php` (verified via `php artisan route:list`)

---

## 1. Public / Home

| Method | URL | Name | Action | Middleware |
|---|---|---|---|---|
| GET | `/` | — | Closure → `view('welcome')` | web |

**Scenario:** App ka default landing page (welcome page), jab koi bhi user root URL pe aata hai.

---

## 2. Super Admin Routes (`prefix: admin`, `name: admin.*`)

### 2.1 Authentication (Guest)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/admin/login` | `admin.login` | `SuperAdminController@loginPage` |
| POST | `/admin/login` | `admin.login.post` | `SuperAdminController@login` |

**Scenario:** Super Admin login page dikhana aur login form submit hone par credentials verify karna.

### 2.2 Protected Admin Routes (Middleware: `auth`, `super_admin`)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/admin/dashboard` | `admin.dashboard` | `SuperAdminController@dashboard` |
| POST | `/admin/logout` | `admin.logout` | `SuperAdminController@logout` |
| POST | `/admin/settings` | `admin.settings.update` | `SuperAdminController@updateSettings` |

**Scenario:** Login hone ke baad admin apna dashboard dekhta hai, logout karta hai, ya apni settings update karta hai (settings jaise site config, PIN, etc.).

### 2.3 Client CRUD (Middleware: `auth`, `super_admin`)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/admin/clients` | `admin.clients.index` | `ClientController@index` |
| GET | `/admin/clients/create` | `admin.clients.create` | `ClientController@create` |
| POST | `/admin/clients` | `admin.clients.store` | `ClientController@store` |
| PATCH | `/admin/clients/{id}/toggle-status` | `admin.clients.toggle-status` | `ClientController@toggleStatus` |

**Scenario:** Admin naye clients dekh sakta hai (list), naya client create karne ka form khol sakta hai, form submit kar ke naya client bana sakta hai, aur kisi client ko active/inactive (toggle) kar sakta hai.

### 2.4 Payments (Middleware: `auth`, `super_admin`)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/admin/payments` | `admin.payments.index` | `PaymentController@index` |

**Scenario:** Admin clients ke payments/transactions ki list dekhta hai.

### 2.5 BG Owner (Middleware: `auth`, `super_admin`)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/admin/bg-owner` | `admin.bg-owner` | `SuperAdminController@bgOwner` |
| POST | `/admin/bg-owner/verify-pin` | `admin.bg-owner.verify-pin` | `SuperAdminController@verifyBgOwnerPin` |

**Scenario:** "BG Owner" (Background/Business Owner) special section — pehle PIN verify hota hai, uske baad hi related page access hota hai (extra security layer, jaise financial ya sensitive info ke liye).

---

## 3. Client Routes (`prefix: client`, `name: client.*`)

### 3.1 Authentication (Guest)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/client/login` | `client.login` | `ClientAuthController@loginPage` |
| POST | `/client/login` | `client.login.post` | `ClientAuthController@login` |

**Scenario:** Client (normal user) login page aur login submit.

### 3.2 Protected Client Routes (Middleware: `auth`)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/client/dashboard` | `client.dashboard` | `ClientAuthController@dashboard` |
| GET | `/client/profile` | `client.profile` | `ClientAuthController@profile` |
| GET | `/client/settings` | `client.settings` | `ClientAuthController@settings` |
| POST | `/client/settings/password` | `client.settings.password` | `ClientAuthController@updatePassword` |
| POST | `/client/logout` | `client.logout` | `ClientAuthController@logout` |

**Scenario:** Login ke baad client apna dashboard dekhta hai, profile dekhta hai, settings page pe ja kar password update karta hai, aur logout karta hai.

### 3.3 Password Reset (Guest)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/client/forgot-password` | `client.forgot-password` | `ClientAuthController@forgotPassword` |
| POST | `/client/forgot-password` | `client.forgot-password.send` | `ClientAuthController@sendResetLink` |
| GET | `/client/reset-password/{token}` | `client.password.reset` | `ClientAuthController@resetPasswordPage` |
| POST | `/client/reset-password` | `client.password.update` | `ClientAuthController@resetPassword` |

**Scenario:** Client password bhool jaye to "forgot password" form se reset link mangwata hai (email pe jata hai), phir link ke token se reset-password page khulta hai jahan naya password set hota hai.

---

## 4. Birthday Card Screens (Public — No Auth)

### 4.1 Page + Variant (Boy)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/boy/page/{page}/{variant}` | `boy.page.variant` | Closure → dynamic view resolve |

**View resolution logic:**
- View name = `birthday.boy-page-{page}` agar `variant == 1`
- View name = `birthday.boy-page-{page}-{variant}` agar `variant != 1`

**Scenario:** Boy ka birthday card flow — page number aur variant number ke hisab se sahi blade view load hoti hai. Misal: `/boy/page/1/1` → `birthday.boy-page-1`, aur `/boy/page/1/2` → `birthday.boy-page-1-2`.

### 4.2 Page + Variant (Girl)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/girl/page/{page}/{variant}` | `girl.page.variant` | Closure → dynamic view resolve |

**Scenario:** Girl ka birthday card flow, same logic jaisa boy ke liye hai.

### 4.3 Gift Pages — Nested (Boy)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/boy/page/{page}/{variant}/gift/{gift}/{giftPage}` | `boy.page.gift` | Closure → dynamic view resolve |

**View resolution:** `birthday.boy-page-{page}-variant-{variant}-gift-{gift}-page-{giftPage}`

**Scenario:** Jab card ke andar "gift" open karne ka option hota hai — is route se specific gift ka specific page load hota hai. Misal: `/boy/page-3/1/gift/1/1` → `birthday.boy-page-3-variant-1-gift-1-page-1`.

### 4.4 Gift Pages — Nested (Girl)

| Method | URL | Name | Action |
|---|---|---|---|
| GET | `/girl/page/{page}/{variant}/gift/{gift}/{giftPage}` | `girl.page.gift` | Closure → dynamic view resolve |

**View resolution:** `birthday.girl-page-{page}-variant-{variant}-gift-{gift}-page-{giftPage}`

**Scenario:** Girl card ke andar gift flow, boy jaisa hi hai.

---

## 5. Framework / System Routes (Auto-registered)

| Method | URL | Name | Purpose |
|---|---|---|---|
| GET/HEAD | `/storage/{path}` | `storage.local` | Local storage disk se file serve karna (dev env) |
| PUT | `/storage/{path}` | `storage.local.upload` | Local storage disk pe file upload (dev env) |
| GET/HEAD | `/up` | — | Laravel health-check endpoint |

---

## Quick Summary Table (Sab Routes Ek Nazar Mein)

| # | Method | URL | Name |
|---|---|---|---|
| 1 | GET | `/` | — |
| 2 | GET | `/admin/login` | admin.login |
| 3 | POST | `/admin/login` | admin.login.post |
| 4 | GET | `/admin/dashboard` | admin.dashboard |
| 5 | POST | `/admin/logout` | admin.logout |
| 6 | POST | `/admin/settings` | admin.settings.update |
| 7 | GET | `/admin/clients` | admin.clients.index |
| 8 | GET | `/admin/clients/create` | admin.clients.create |
| 9 | POST | `/admin/clients` | admin.clients.store |
| 10 | PATCH | `/admin/clients/{id}/toggle-status` | admin.clients.toggle-status |
| 11 | GET | `/admin/payments` | admin.payments.index |
| 12 | GET | `/admin/bg-owner` | admin.bg-owner |
| 13 | POST | `/admin/bg-owner/verify-pin` | admin.bg-owner.verify-pin |
| 14 | GET | `/client/login` | client.login |
| 15 | POST | `/client/login` | client.login.post |
| 16 | GET | `/client/dashboard` | client.dashboard |
| 17 | GET | `/client/profile` | client.profile |
| 18 | GET | `/client/settings` | client.settings |
| 19 | POST | `/client/settings/password` | client.settings.password |
| 20 | POST | `/client/logout` | client.logout |
| 21 | GET | `/client/forgot-password` | client.forgot-password |
| 22 | POST | `/client/forgot-password` | client.forgot-password.send |
| 23 | GET | `/client/reset-password/{token}` | client.password.reset |
| 24 | POST | `/client/reset-password` | client.password.update |
| 25 | GET | `/boy/page/{page}/{variant}` | boy.page.variant |
| 26 | GET | `/girl/page/{page}/{variant}` | girl.page.variant |
| 27 | GET | `/boy/page/{page}/{variant}/gift/{gift}/{giftPage}` | boy.page.gift |
| 28 | GET | `/girl/page/{page}/{variant}/gift/{gift}/{giftPage}` | girl.page.gift |
| 29 | GET/HEAD/PUT | `/storage/{path}` | storage.local(.upload) |
| 30 | GET | `/up` | — |
