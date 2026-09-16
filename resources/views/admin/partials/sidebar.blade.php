{{-- The one Super Admin sidebar.

     Every admin screen includes this, so the menu can no longer drift page to
     page — which is what used to make items appear and disappear as you
     clicked around. The active item is worked out from the current route
     rather than hard-coded per page. --}}
@php
    $navItems = [
        ['route' => 'admin.dashboard',        'match' => 'admin.dashboard',        'icon' => '🏠', 'label' => 'Dashboard'],
        ['route' => 'admin.clients.index',    'match' => 'admin.clients.*',        'icon' => '👥', 'label' => 'All Clients'],
        ['route' => 'admin.subscriptions.index', 'match' => 'admin.subscriptions.*', 'icon' => '🎫', 'label' => 'Subscriptions'],
        ['route' => 'admin.links.index',      'match' => 'admin.links.*',          'icon' => '🔗', 'label' => 'Generated Links'],
        ['route' => 'admin.payments.index',   'match' => 'admin.payments.*',       'icon' => '💰', 'label' => 'Payments'],
        ['route' => 'admin.plans.index',      'match' => 'admin.plans.*',          'icon' => '🎟️', 'label' => 'Plans'],
        ['route' => 'admin.payment-methods.index', 'match' => ['admin.payment-methods.*', 'admin.support-contacts.*'], 'icon' => '💳', 'label' => 'Payment Methods'],
        ['route' => 'admin.bg-owner',         'match' => 'admin.bg-owner*',        'icon' => '🔒', 'label' => 'BG Owner'],
        ['route' => 'admin.music.index',      'match' => 'admin.music.*',          'icon' => '🎵', 'label' => 'Music Library'],
    ];
@endphp

<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-logo">
        {{-- The app tile already is a rounded purple square, so it drops
             straight into the 36px mark slot; the accent word keeps admins
             sure which side of the product they are on. --}}
        <img src="{{ asset('images/logo/clean/appicon.png') }}" alt="" class="logo-mark"
            style="object-fit: contain; background: none; box-shadow: none;">
        <div class="logo-text">Giftloft<span> Admin</span></div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu</div>
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
                class="nav-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                <div class="nav-icon">{{ $item['icon'] }}</div> {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="sidebar-user">
        <div class="user-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div class="user-meta">
            <strong>{{ Auth::user()->name }}</strong>
            <span>{{ Auth::user()->role }}</span>
        </div>
        <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" title="Logout">↩</button>
        </form>
    </div>
</aside>

{{-- Mobile: the sidebar slides in over the page, so it needs a trigger and a
     backdrop to dismiss it. Both are inert on desktop. --}}
<button class="sidebar-toggle" id="adminSidebarToggle" type="button"
    aria-label="Open menu" aria-controls="adminSidebar" aria-expanded="false">☰</button>
<div class="sidebar-backdrop" id="adminSidebarBackdrop" hidden></div>

<style>
    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 130;
        width: 42px;
        height: 42px;
        border-radius: 11px;
        border: 1.5px solid var(--border, #e4e9f4);
        background: var(--surface, #fff);
        color: var(--text, #111827);
        font-size: 1.15rem;
        line-height: 1;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(100, 116, 180, .14);
    }

    .sidebar-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, .45);
        z-index: 110;
    }

    @media (max-width: 900px) {
        .sidebar-toggle {
            display: block;
        }

        .sidebar {
            transform: translateX(-100%);
            transition: transform .26s ease;
            z-index: 120;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-backdrop.open {
            display: block;
        }

        /* The fixed sidebar no longer reserves a column, and the toggle needs
           room above the page heading. Most screens use `.main`; the music
           library uses a bare <main>. */
        .main,
        body > main {
            margin-left: 0 !important;
            padding-top: 4.2rem !important;
        }
    }
</style>

<script>
    (function () {
        const sidebar = document.getElementById('adminSidebar');
        const toggle = document.getElementById('adminSidebarToggle');
        const backdrop = document.getElementById('adminSidebarBackdrop');
        if (!sidebar || !toggle || !backdrop) return;

        const setOpen = (open) => {
            sidebar.classList.toggle('open', open);
            backdrop.classList.toggle('open', open);
            backdrop.hidden = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        toggle.addEventListener('click', () => setOpen(!sidebar.classList.contains('open')));
        backdrop.addEventListener('click', () => setOpen(false));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') setOpen(false);
        });
        // Going back to a wide window should never leave the page stuck behind
        // a backdrop that is no longer visible.
        window.addEventListener('resize', () => {
            if (window.innerWidth > 900) setOpen(false);
        });
    })();
</script>
