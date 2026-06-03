# Birthday Card Routes & Pages Documentation

## Overview

This document outlines the complete structure of birthday card routes, pages, variants, and gifts.

**Total Pages Created:** 48 files

- Boys: 24 pages
- Girls: 24 pages

---

## Route Structure

### New Gift Routes Pattern

```
Base URL: http://127.0.0.1:8000

Route Pattern:
/boy/page/{page}/{variant}/gift/{gift}/{giftPage}
/girl/page/{page}/{variant}/gift/{gift}/{giftPage}

Parameters:
- page: Page number (e.g., 3)
- variant: Card variant/theme (1 or 2)
- gift: Gift number (1, 2, or 3)
- giftPage: Individual page within gift (1, 2, 3, or 4)
```

---

## Boys Birthday Card Routes

### Page 3 - Variant 1 (12 pages)

**Base URL:** `/boy/page/3/1/gift`

| Gift       | Page 1                   | Page 2                   | Page 3                   | Page 4                   |
| ---------- | ------------------------ | ------------------------ | ------------------------ | ------------------------ |
| **Gift 1** | `/boy/page/3/1/gift/1/1` | `/boy/page/3/1/gift/1/2` | `/boy/page/3/1/gift/1/3` | `/boy/page/3/1/gift/1/4` |
| **Gift 2** | `/boy/page/3/1/gift/2/1` | `/boy/page/3/1/gift/2/2` | `/boy/page/3/1/gift/2/3` | `/boy/page/3/1/gift/2/4` |
| **Gift 3** | `/boy/page/3/1/gift/3/1` | `/boy/page/3/1/gift/3/2` | `/boy/page/3/1/gift/3/3` | `/boy/page/3/1/gift/3/4` |

**View Files Location:** `resources/views/birthday/`

- `boy-page-3-variant-1-gift-1-page-1.blade.php`
- `boy-page-3-variant-1-gift-1-page-2.blade.php`
- `boy-page-3-variant-1-gift-1-page-3.blade.php`
- `boy-page-3-variant-1-gift-1-page-4.blade.php`
- `boy-page-3-variant-1-gift-2-page-1.blade.php` ... (and so on)

---

### Page 3 - Variant 2 (12 pages)

**Base URL:** `/boy/page/3/2/gift`

| Gift       | Page 1                   | Page 2                   | Page 3                   | Page 4                   |
| ---------- | ------------------------ | ------------------------ | ------------------------ | ------------------------ |
| **Gift 1** | `/boy/page/3/2/gift/1/1` | `/boy/page/3/2/gift/1/2` | `/boy/page/3/2/gift/1/3` | `/boy/page/3/2/gift/1/4` |
| **Gift 2** | `/boy/page/3/2/gift/2/1` | `/boy/page/3/2/gift/2/2` | `/boy/page/3/2/gift/2/3` | `/boy/page/3/2/gift/2/4` |
| **Gift 3** | `/boy/page/3/2/gift/3/1` | `/boy/page/3/2/gift/3/2` | `/boy/page/3/2/gift/3/3` | `/boy/page/3/2/gift/3/4` |

**View Files Location:** `resources/views/birthday/`

- `boy-page-3-variant-2-gift-1-page-1.blade.php`
- `boy-page-3-variant-2-gift-1-page-2.blade.php`
- ... (and so on)

---

## Girls Birthday Card Routes

### Page 3 - Variant 1 (12 pages)

**Base URL:** `/girl/page/3/1/gift`

| Gift       | Page 1                    | Page 2                    | Page 3                    | Page 4                    |
| ---------- | ------------------------- | ------------------------- | ------------------------- | ------------------------- |
| **Gift 1** | `/girl/page/3/1/gift/1/1` | `/girl/page/3/1/gift/1/2` | `/girl/page/3/1/gift/1/3` | `/girl/page/3/1/gift/1/4` |
| **Gift 2** | `/girl/page/3/1/gift/2/1` | `/girl/page/3/1/gift/2/2` | `/girl/page/3/1/gift/2/3` | `/girl/page/3/1/gift/2/4` |
| **Gift 3** | `/girl/page/3/1/gift/3/1` | `/girl/page/3/1/gift/3/2` | `/girl/page/3/1/gift/3/3` | `/girl/page/3/1/gift/3/4` |

**View Files Location:** `resources/views/birthday/`

- `girl-page-3-variant-1-gift-1-page-1.blade.php`
- `girl-page-3-variant-1-gift-1-page-2.blade.php`
- ... (and so on)

---

### Page 3 - Variant 2 (12 pages)

**Base URL:** `/girl/page/3/2/gift`

| Gift       | Page 1                    | Page 2                    | Page 3                    | Page 4                    |
| ---------- | ------------------------- | ------------------------- | ------------------------- | ------------------------- |
| **Gift 1** | `/girl/page/3/2/gift/1/1` | `/girl/page/3/2/gift/1/2` | `/girl/page/3/2/gift/1/3` | `/girl/page/3/2/gift/1/4` |
| **Gift 2** | `/girl/page/3/2/gift/2/1` | `/girl/page/3/2/gift/2/2` | `/girl/page/3/2/gift/2/3` | `/girl/page/3/2/gift/2/4` |
| **Gift 3** | `/girl/page/3/2/gift/3/1` | `/girl/page/3/2/gift/3/2` | `/girl/page/3/2/gift/3/3` | `/girl/page/3/2/gift/3/4` |

**View Files Location:** `resources/views/birthday/`

- `girl-page-3-variant-2-gift-1-page-1.blade.php`
- `girl-page-3-variant-2-gift-1-page-2.blade.php`
- ... (and so on)

---

## Route Definition (web.php)

```php
// ─── Gift Pages (New Structure) ────────────────────────────────
Route::get('/boy/page/{page}/{variant}/gift/{gift}/{giftPage}', function ($page, $variant, $gift, $giftPage) {
    $viewName = 'birthday.boy-page-' . $page . '-variant-' . $variant . '-gift-' . $gift . '-page-' . $giftPage;
    return view($viewName);
})->name('boy.page.gift');

Route::get('/girl/page/{page}/{variant}/gift/{gift}/{giftPage}', function ($page, $variant, $gift, $giftPage) {
    $viewName = 'birthday.girl-page-' . $page . '-variant-' . $variant . '-gift-' . $gift . '-page-' . $giftPage;
    return view($viewName);
})->name('girl.page.gift');
```

---

## View File Naming Convention

### Pattern

```
{gender}-page-{page}-variant-{variant}-gift-{gift}-page-{giftPage}.blade.php
```

### Examples

- `boy-page-3-variant-1-gift-1-page-1.blade.php` → Route: `/boy/page/3/1/gift/1/1`
- `boy-page-3-variant-2-gift-3-page-4.blade.php` → Route: `/boy/page/3/2/gift/3/4`
- `girl-page-3-variant-1-gift-2-page-3.blade.php` → Route: `/girl/page/3/1/gift/2/3`
- `girl-page-3-variant-2-gift-1-page-2.blade.php` → Route: `/girl/page/3/2/gift/1/2`

---

## Summary

| Category                  | Count                                                 |
| ------------------------- | ----------------------------------------------------- |
| Total Pages               | 48                                                    |
| Boys Pages                | 24                                                    |
| Girls Pages               | 24                                                    |
| Variants per Gender       | 2                                                     |
| Gifts per Variant         | 3                                                     |
| Individual Pages per Gift | 4                                                     |
| **Formula**               | 2 genders × 2 variants × 3 gifts × 4 pages = 48 total |

---

## Directory Structure

```
resources/views/birthday/
├── boy-page-3-variant-1-gift-1-page-1.blade.php
├── boy-page-3-variant-1-gift-1-page-2.blade.php
├── boy-page-3-variant-1-gift-1-page-3.blade.php
├── boy-page-3-variant-1-gift-1-page-4.blade.php
├── boy-page-3-variant-1-gift-2-page-1.blade.php
├── ... (12 more boy variant 1 files)
├── boy-page-3-variant-2-gift-1-page-1.blade.php
├── ... (12 boy variant 2 files)
├── girl-page-3-variant-1-gift-1-page-1.blade.php
├── ... (12 girl variant 1 files)
├── girl-page-3-variant-2-gift-1-page-1.blade.php
└── ... (12 girl variant 2 files)
```

---

## Notes

- All view files are located in `resources/views/birthday/`
- Each gift has 4 separate pages for detailed presentation
- Both boys and girls have identical structure with 2 variant themes
- Page 3 is used as base; can be extended to other pages following same pattern
