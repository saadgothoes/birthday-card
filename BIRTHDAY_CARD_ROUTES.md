# Birthday Card — All Page URLs (Boy + Girl)

Base URL: `http://127.0.0.1:8000`

Route pattern (from `routes/web.php`):
- Normal page: `/{boy|girl}/page/{page}/{variant}`
- Gift page: `/{boy|girl}/page/{page}/{variant}/gift/{gift}/{giftPage}`

Variant `1` = default view (`birthday.boy-page-{page}` / `birthday.girl-page-{page}`), variant `2` (ya koi bhi non-1) = alag color theme wali view (`...-page-{page}-{variant}`).

---

## BOY — Variant 1 (Default Theme)

| # | URL | Blade View |
|---|---|---|
| 1 | `/boy/page/1/1` | `birthday.boy-page-1` |
| 2 | `/boy/page/2/1` | `birthday.boy-page-2` |
| 3 | `/boy/page/3/1` | `birthday.boy-page-3` |

## BOY — Variant 2 (Color Theme 2)

| # | URL | Blade View |
|---|---|---|
| 4 | `/boy/page/1/2` | `birthday.boy-page-1-2` |
| 5 | `/boy/page/2/2` | `birthday.boy-page-2-2` |
| 6 | `/boy/page/3/2` | `birthday.boy-page-3-2` |

## BOY — Gift Pages (sirf Page 3 ke liye) — Variant 1

**Gift 1** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 7 | `/boy/page/3/1/gift/1/1` | `birthday.boy-page-3-variant-1-gift-1-page-1` |
| 8 | `/boy/page/3/1/gift/1/2` | `birthday.boy-page-3-variant-1-gift-1-page-2` |
| 9 | `/boy/page/3/1/gift/1/3` | `birthday.boy-page-3-variant-1-gift-1-page-3` |
| 10 | `/boy/page/3/1/gift/1/4` | `birthday.boy-page-3-variant-1-gift-1-page-4` |

**Gift 2** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 11 | `/boy/page/3/1/gift/2/1` | `birthday.boy-page-3-variant-1-gift-2-page-1` |
| 12 | `/boy/page/3/1/gift/2/2` | `birthday.boy-page-3-variant-1-gift-2-page-2` |
| 13 | `/boy/page/3/1/gift/2/3` | `birthday.boy-page-3-variant-1-gift-2-page-3` |
| 14 | `/boy/page/3/1/gift/2/4` | `birthday.boy-page-3-variant-1-gift-2-page-4` |

**Gift 3** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 15 | `/boy/page/3/1/gift/3/1` | `birthday.boy-page-3-variant-1-gift-3-page-1` |
| 16 | `/boy/page/3/1/gift/3/2` | `birthday.boy-page-3-variant-1-gift-3-page-2` |
| 17 | `/boy/page/3/1/gift/3/3` | `birthday.boy-page-3-variant-1-gift-3-page-3` |
| 18 | `/boy/page/3/1/gift/3/4` | `birthday.boy-page-3-variant-1-gift-3-page-4` |

## BOY — Gift Pages — Variant 2 (Color Theme 2)

**Gift 1** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 19 | `/boy/page/3/2/gift/1/1` | `birthday.boy-page-3-variant-2-gift-1-page-1` |
| 20 | `/boy/page/3/2/gift/1/2` | `birthday.boy-page-3-variant-2-gift-1-page-2` |
| 21 | `/boy/page/3/2/gift/1/3` | `birthday.boy-page-3-variant-2-gift-1-page-3` |
| 22 | `/boy/page/3/2/gift/1/4` | `birthday.boy-page-3-variant-2-gift-1-page-4` |

**Gift 2** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 23 | `/boy/page/3/2/gift/2/1` | `birthday.boy-page-3-variant-2-gift-2-page-1` |
| 24 | `/boy/page/3/2/gift/2/2` | `birthday.boy-page-3-variant-2-gift-2-page-2` |
| 25 | `/boy/page/3/2/gift/2/3` | `birthday.boy-page-3-variant-2-gift-2-page-3` |
| 26 | `/boy/page/3/2/gift/2/4` | `birthday.boy-page-3-variant-2-gift-2-page-4` |

**Gift 3** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 27 | `/boy/page/3/2/gift/3/1` | `birthday.boy-page-3-variant-2-gift-3-page-1` |
| 28 | `/boy/page/3/2/gift/3/2` | `birthday.boy-page-3-variant-2-gift-3-page-2` |
| 29 | `/boy/page/3/2/gift/3/3` | `birthday.boy-page-3-variant-2-gift-3-page-3` |
| 30 | `/boy/page/3/2/gift/3/4` | `birthday.boy-page-3-variant-2-gift-3-page-4` |

---

## GIRL — Variant 1 (Default Theme)

| # | URL | Blade View |
|---|---|---|
| 31 | `/girl/page/1/1` | `birthday.girl-page-1` |
| 32 | `/girl/page/2/1` | `birthday.girl-page-2` |
| 33 | `/girl/page/3/1` | `birthday.girl-page-3` |

## GIRL — Variant 2 (Color Theme 2)

| # | URL | Blade View |
|---|---|---|
| 34 | `/girl/page/1/2` | `birthday.girl-page-1-2` |
| 35 | `/girl/page/2/2` | `birthday.girl-page-2-2` |
| 36 | `/girl/page/3/2` | `birthday.girl-page-3-2` |

## GIRL — Gift Pages (sirf Page 3 ke liye) — Variant 1

**Gift 1** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 37 | `/girl/page/3/1/gift/1/1` | `birthday.girl-page-3-variant-1-gift-1-page-1` |
| 38 | `/girl/page/3/1/gift/1/2` | `birthday.girl-page-3-variant-1-gift-1-page-2` |
| 39 | `/girl/page/3/1/gift/1/3` | `birthday.girl-page-3-variant-1-gift-1-page-3` |
| 40 | `/girl/page/3/1/gift/1/4` | `birthday.girl-page-3-variant-1-gift-1-page-4` |

**Gift 2** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 41 | `/girl/page/3/1/gift/2/1` | `birthday.girl-page-3-variant-1-gift-2-page-1` |
| 42 | `/girl/page/3/1/gift/2/2` | `birthday.girl-page-3-variant-1-gift-2-page-2` |
| 43 | `/girl/page/3/1/gift/2/3` | `birthday.girl-page-3-variant-1-gift-2-page-3` |
| 44 | `/girl/page/3/1/gift/2/4` | `birthday.girl-page-3-variant-1-gift-2-page-4` |

**Gift 3** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 45 | `/girl/page/3/1/gift/3/1` | `birthday.girl-page-3-variant-1-gift-3-page-1` |
| 46 | `/girl/page/3/1/gift/3/2` | `birthday.girl-page-3-variant-1-gift-3-page-2` |
| 47 | `/girl/page/3/1/gift/3/3` | `birthday.girl-page-3-variant-1-gift-3-page-3` |
| 48 | `/girl/page/3/1/gift/3/4` | `birthday.girl-page-3-variant-1-gift-3-page-4` |

## GIRL — Gift Pages — Variant 2 (Color Theme 2)

**Gift 1** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 49 | `/girl/page/3/2/gift/1/1` | `birthday.girl-page-3-variant-2-gift-1-page-1` |
| 50 | `/girl/page/3/2/gift/1/2` | `birthday.girl-page-3-variant-2-gift-1-page-2` |
| 51 | `/girl/page/3/2/gift/1/3` | `birthday.girl-page-3-variant-2-gift-1-page-3` |
| 52 | `/girl/page/3/2/gift/1/4` | `birthday.girl-page-3-variant-2-gift-1-page-4` |

**Gift 2** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 53 | `/girl/page/3/2/gift/2/1` | `birthday.girl-page-3-variant-2-gift-2-page-1` |
| 54 | `/girl/page/3/2/gift/2/2` | `birthday.girl-page-3-variant-2-gift-2-page-2` |
| 55 | `/girl/page/3/2/gift/2/3` | `birthday.girl-page-3-variant-2-gift-2-page-3` |
| 56 | `/girl/page/3/2/gift/2/4` | `birthday.girl-page-3-variant-2-gift-2-page-4` |

**Gift 3** (4 pages)
| # | URL | Blade View |
|---|---|---|
| 57 | `/girl/page/3/2/gift/3/1` | `birthday.girl-page-3-variant-2-gift-3-page-1` |
| 58 | `/girl/page/3/2/gift/3/2` | `birthday.girl-page-3-variant-2-gift-3-page-2` |
| 59 | `/girl/page/3/2/gift/3/3` | `birthday.girl-page-3-variant-2-gift-3-page-3` |
| 60 | `/girl/page/3/2/gift/3/4` | `birthday.girl-page-3-variant-2-gift-3-page-4` |

---

## Note

`boy-gift-1-1.blade.php`, `boy-gift-1-2.blade.php`, `boy-gift-1-3.blade.php` aur inke girl equivalents (`girl-gift-1-1/2/3.blade.php`) bhi resources/views/birthday mein maujood hain, lekin `web.php` mein inko directly resolve karne wala koi route registered nahi hai — sirf upar wala nested `/page/{page}/{variant}/gift/{gift}/{giftPage}` pattern hi actual working URLs banata hai.
