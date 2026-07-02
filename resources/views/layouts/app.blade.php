<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/spampk_logo.png') }}">
    <title>@yield('title', 'SPAMPK') &middot; SPAMPK</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
@php($u = auth()->user())
<div class="app">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">
            <img class="brand-logo" src="{{ asset('images/spampk_logo.png') }}" alt="SPAMPK">
            <button type="button" class="sidebar__close" id="sidebarClose" aria-label="Tutup menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <nav class="nav">
            <div class="nav__label">Menu</div>
            <a href="{{ route('dashboard') }}" class="nav__link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                Papan Pemuka
            </a>
            <a href="{{ route('reports.index') }}" class="nav__link {{ request()->routeIs('reports.*') ? 'is-active' : '' }}">
                Laporan
            </a>

            @if ($u->isPentadbir())
                <div class="nav__label">Pentadbiran</div>
                <a href="{{ route('users.index') }}" class="nav__link {{ request()->routeIs('users.*') ? 'is-active' : '' }}">
                    Pengurusan Pengguna
                </a>
            @endif
        </nav>

       <div class="sidebar__foot">
    <div class="userbox">
        <div class="avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
        <div class="userbox__meta">
            <strong>{{ $u->name }}</strong>
            <span>{{ $u->roleLabel() }}</span>
        </div>
    </div>
    <a href="{{ route('profile.edit') }}" class="btn btn--ghost btn--sm btn--block" style="margin-bottom:.5rem;">
        Profil Pengguna
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn--ghost btn--sm btn--block">Log Keluar</button>
    </form>
</div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar__left">
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div class="topbar__title">@yield('title', 'Papan Pemuka')</div>
            </div>
        </header>

        <main class="content">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>