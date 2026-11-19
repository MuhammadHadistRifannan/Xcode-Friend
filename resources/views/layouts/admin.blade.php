@php
    $activeTheme = \App\Helpers\SettingHelper::get('theme_color', 'red');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $activeTheme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - XCODE')</title>
    <!-- Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400..800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Geist', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } },
        }
    </script>
    {{-- Admin shell, diadaptasi dari design system Generator Sertifikat XCODE dengan palet XCODE Friends. --}}
    <style>
        :root {
            --brand: #990000;
            --brand-hover: #7a0000;
            --brand-soft: #fdecec;
            --side: #141416;
            --panel: #f4f4f5;
            --raised: #ffffff;
            --line: #e4e4e7;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-card: 0 1px 2px rgb(24 24 27 / 0.04), 0 8px 24px -12px rgb(24 24 27 / 0.12);
            --shadow-pop: 0 24px 64px -16px rgb(24 24 27 / 0.28);
        }
        .admin-shell { background: var(--side); }
        .admin-side { background: var(--side); position: relative; isolation: isolate; }
        .admin-glow {
            position: absolute; z-index: -1; width: 280px; height: 280px; left: -110px; bottom: 40px;
            border-radius: 999px; background: radial-gradient(circle, rgb(183 28 28 / 0.55), transparent 70%);
            filter: blur(40px); pointer-events: none;
        }
        .admin-panel { background: var(--panel); isolation: isolate; }
        @media (min-width: 1024px) {
            .admin-panel { box-shadow: 0 0 0 1px rgb(255 255 255 / 0.04); }
            .admin-panel::before {
                content: ''; position: absolute; top: 0; left: 0; width: 240px; height: 240px; z-index: -1;
                border-top-left-radius: 28px; pointer-events: none;
                background: radial-gradient(circle at 0 0, var(--brand-soft), transparent 70%);
            }
        }
        .admin-link {
            display: flex; align-items: center; gap: 0.75rem; height: 42px; padding: 0 1rem;
            border-radius: 999px; color: #a1a1aa; font-size: 0.875rem; font-weight: 500;
            transition: color 0.2s, background-color 0.2s;
        }
        .admin-link:hover { color: #fafafa; background: rgb(255 255 255 / 0.06); }
        .admin-link[aria-current='page'] { color: #18181b; background: #ffffff; font-weight: 600; }
        .admin-link[aria-current='page'] svg { color: var(--brand); }
        .admin-scroll { scrollbar-width: thin; scrollbar-color: #3f3f46 transparent; }
        .admin-promo {
            overflow: hidden; isolation: isolate; border-radius: 24px; padding: 1.25rem; color: #fff;
            background: linear-gradient(160deg, #d32f2f -20%, var(--brand) 45%, #4a0000 120%);
        }
        .admin-promo::before {
            content: ''; position: absolute; right: -40px; bottom: -60px; width: 180px; height: 180px; z-index: -1;
            border-radius: 999px; background: repeating-radial-gradient(circle, rgb(255 255 255 / 0.14) 0 1px, transparent 1px 16px);
        }
        /* Layar pendek: menu lebih penting daripada kartu promo. */
        @media (max-height: 820px) { .admin-promo-side { display: none; } }
        .admin-search {
            display: flex; align-items: center; gap: 0.5rem; height: 44px; padding: 0 1rem; border-radius: 999px;
            background: var(--raised); box-shadow: var(--shadow-card); color: #71717a; transition: box-shadow 0.2s;
        }
        .admin-search:focus-within { box-shadow: 0 0 0 4px var(--brand-soft), var(--shadow-card); }
        .admin-search input { background: transparent; border: 0; outline: none; width: 100%; font-size: 0.875rem; color: #18181b; }
        .admin-search input::placeholder { color: #71717a; }
        .admin-round {
            display: grid; place-items: center; width: 44px; height: 44px; border-radius: 999px;
            background: var(--raised); box-shadow: var(--shadow-card); color: #18181b; transition: transform 0.2s var(--ease-out), color 0.2s;
        }
        .admin-round:hover { transform: translateY(-1px); color: var(--brand); }
        .admin-account > summary { list-style: none; cursor: pointer; }
        .admin-account > summary::-webkit-details-marker { display: none; }
        .admin-pop {
            position: absolute; z-index: 40; top: calc(100% + 8px); left: 0; min-width: 220px; padding: 6px;
            background: var(--raised); border: 1px solid var(--line); border-radius: 16px; box-shadow: var(--shadow-pop);
        }
        .admin-pop-item {
            display: flex; align-items: center; gap: 0.625rem; padding: 0.625rem 0.75rem; border-radius: 10px;
            font-size: 0.875rem; font-weight: 500; color: #3f3f46; text-align: left;
        }
        .admin-pop-item:hover { background: #f4f4f5; }
        /* Kartu statistik (padanan komponen kpi sertigenerator) */
        .admin-tile {
            position: relative; overflow: hidden; border-radius: 20px; padding: 1.25rem; background: var(--raised);
            box-shadow: var(--shadow-card); transition: transform 0.25s var(--ease-out), box-shadow 0.25s;
        }
        a.admin-tile:hover { transform: translateY(-2px); box-shadow: 0 1px 2px rgb(24 24 27 / 0.04), 0 16px 32px -16px rgb(122 0 0 / 0.25); }
        .admin-tile-icon { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 12px; color: #fff; }
        [data-reveal] { animation: admin-rise 0.55s var(--ease-out) backwards; animation-delay: calc(var(--i, 0) * 50ms); }
        @keyframes admin-rise { from { opacity: 0; transform: translateY(12px); } }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { animation: none; }
            .admin-round, .admin-tile, .admin-link { transition: none; }
        }
        html.dark { --panel: #121212; --raised: #1e1e1e; --line: #333333; --brand-soft: rgb(153 0 0 / 0.18); }
        html.dark .admin-search input, html.dark .admin-round { color: #f5f5f5; }
        html.dark .admin-pop-item { color: #e5e5e5; }
        html.dark .admin-pop-item:hover { background: #2a2a2a; }
        html.dark .text-zinc-900 { color: #f5f5f5; }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js & Anti-Flicker Cloak -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if($activeTheme === 'dark')
    <style>
        :root, html.dark {
            color-scheme: dark;
        }

        /* ── Base Layout ── */
        body, main, aside {
            background-color: #121212 !important;
            color: #e5e5e5 !important;
        }
        :not(.no-theme).bg-\[\#fafafa\],
        :not(.no-theme).bg-\[\#FAFAFA\],
        :not(.no-theme).bg-\[\#f5f5f5\],
        :not(.no-theme)[class*="bg-[#f5f5f5]"] {
            background-color: #121212 !important;
        }

        /* ── Cards & Surfaces ── */
        :not(.no-theme).bg-white,
        :not(.no-theme).bg-\[\#f9f9f9\],
        :not(.no-theme).bg-\[\#fcfcfc\] {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
            color: #e5e5e5 !important;
        }
        :not(.no-theme).bg-gray-50,
        :not(.no-theme).bg-neutral-50,
        :not(.no-theme).bg-\[\#f8f9fa\],
        :not(.no-theme)[class*="bg-[#f8f9fa]"] {
            background-color: #262626 !important;
            border-color: #383838 !important;
        }
        :not(.no-theme).bg-gray-100,
        :not(.no-theme).bg-neutral-100 {
            background-color: #2a2a2a !important;
            border-color: #383838 !important;
        }
        :not(.no-theme).bg-gray-200,
        :not(.no-theme).bg-neutral-200 {
            background-color: #333333 !important;
            border-color: #444444 !important;
        }

        /* ── Form Controls ── */
        :not(.no-theme) input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        :not(.no-theme) select,
        :not(.no-theme) textarea {
            background-color: #252525 !important;
            border-color: #3f3f3f !important;
            color: #f5f5f5 !important;
        }
        :not(.no-theme) input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]):not([type="file"]):focus,
        :not(.no-theme) select:focus,
        :not(.no-theme) textarea:focus {
            background-color: #2b2b2b !important;
            border-color: #b71c1c !important;
            color: #ffffff !important;
        }
        :not(.no-theme) input::placeholder,
        :not(.no-theme) textarea::placeholder {
            color: #737373 !important;
        }
        :not(.no-theme) select option {
            background-color: #252525 !important;
            color: #f5f5f5 !important;
        }

        /* ── Text Colors ── */
        :not(.no-theme).text-black,
        :not(.no-theme).text-gray-900,
        :not(.no-theme).text-neutral-900,
        :not(.no-theme).text-slate-900,
        :not(.no-theme).text-gray-800,
        :not(.no-theme).text-neutral-800,
        :not(.no-theme).text-slate-800,
        :not(.no-theme).text-gray-700,
        :not(.no-theme).text-neutral-700,
        :not(.no-theme).text-slate-700,
        :not(.no-theme).text-\[\#333333\],
        :not(.no-theme)[class*="text-[#333333]"] {
            color: #f5f5f5 !important;
        }
        :not(.no-theme).text-gray-600,
        :not(.no-theme).text-neutral-600,
        :not(.no-theme).text-slate-600,
        :not(.no-theme).text-gray-500,
        :not(.no-theme).text-neutral-500,
        :not(.no-theme).text-slate-500 {
            color: #a3a3a3 !important;
        }
        :not(.no-theme).text-gray-400,
        :not(.no-theme).text-neutral-400 {
            color: #808080 !important;
        }

        /* ── Borders ── */
        :not(.no-theme).border-gray-100,
        :not(.no-theme).border-gray-200,
        :not(.no-theme).border-gray-300,
        :not(.no-theme).border-neutral-100,
        :not(.no-theme).border-neutral-200,
        :not(.no-theme).border-neutral-300,
        :not(.no-theme).border-\[\#e5e5e5\],
        :not(.no-theme)[class*="border-[#e5e5e5]"] {
            border-color: #333333 !important;
        }
        :not(.no-theme).divide-gray-100 > *,
        :not(.no-theme).divide-gray-200 > *,
        :not(.no-theme).divide-neutral-100 > * {
            border-color: #333333 !important;
        }

        /* ── Hover ── */
        :not(.no-theme).hover\:bg-gray-50:hover,
        :not(.no-theme).hover\:bg-neutral-50:hover {
            background-color: #2a2a2a !important;
        }
        :not(.no-theme).hover\:bg-gray-100:hover,
        :not(.no-theme).hover\:bg-neutral-100:hover {
            background-color: #333333 !important;
        }
        :not(.no-theme).hover\:bg-gray-200:hover,
        :not(.no-theme).hover\:bg-neutral-200:hover {
            background-color: #383838 !important;
        }
        :not(.no-theme).hover\:text-gray-800:hover,
        :not(.no-theme).hover\:text-neutral-800:hover {
            color: #ffffff !important;
        }

        /* ── SweetAlert2 Dark ── */
        .swal2-popup {
            background-color: #1e1e1e !important;
            color: #f5f5f5 !important;
            border: 1px solid #333 !important;
        }
        .swal2-title, .swal2-html-container {
            color: #f5f5f5 !important;
        }
    </style>
    @elseif($activeTheme === 'blue')
    <style>
        :not(.no-theme).text-red-600, :not(.no-theme).text-red-500, :not(.no-theme).text-red-700, :not(.no-theme).text-\[\#b90000\], :not(.no-theme).text-\[\#cc0000\] { color: #2563eb !important; }
        :not(.no-theme).bg-red-600, :not(.no-theme).bg-\[\#b90000\], :not(.no-theme).bg-\[\#cc0000\], :not(.no-theme).bg-\[\#990000\] { background-color: #2563eb !important; color: white !important; }
        :not(.no-theme).bg-red-50 { background-color: #eff6ff !important; color: #1d4ed8 !important; }
        :not(.no-theme).bg-red-100 { background-color: #dbeafe !important; color: #1e40af !important; }
        :not(.no-theme).hover\:bg-red-700:hover, :not(.no-theme).hover\:bg-\[\#a00000\]:hover { background-color: #1d4ed8 !important; }
        :not(.no-theme).hover\:bg-red-100:hover { background-color: #dbeafe !important; }
        :not(.no-theme).hover\:bg-red-50:hover { background-color: #eff6ff !important; }
        :not(.no-theme).hover\:text-red-500:hover, :not(.no-theme).hover\:text-red-400:hover { color: #60a5fa !important; }
        :not(.no-theme).border-red-700, :not(.no-theme).border-\[\#cc0000\] { border-color: #2563eb !important; }
        :not(.no-theme).focus\:border-\[\#cc0000\]:focus, :not(.no-theme).focus\:ring-\[\#cc0000\]:focus { border-color: #2563eb !important; --tw-ring-color: #2563eb !important; }
    </style>
    @endif
    @if($activeTheme !== 'dark')
    <style> body { background-color: #f5f5f5; } </style>
    @endif
</head>
@php
    $adminUser = auth()->user();
    $adminName = $adminUser->fullname ?: $adminUser->username;
    $navGroups = [
        'Utama' => [
            ['admin.dashboard', 'Dashboard', 'layout-dashboard', ['admin.dashboard', 'admin.index']],
            ['admin.site-config', 'Site Config', 'settings', ['admin.site-config']],
            ['admin.modules', 'Modules', 'box', ['admin.modules']],
            ['admin.themes', 'Themes', 'palette', ['admin.themes']],
            ['admin.themes.blocks', 'Manage Blocks', 'layout-template', ['admin.themes.blocks']],
        ],
        'Komunitas' => [
            ['admin.members', 'Members', 'users', ['admin.members', 'admin.members.show']],
            ['admin.stream-monitor', 'Stream Monitor', 'activity', ['admin.stream-monitor']],
            ['admin.reports', 'Reports', 'alert-triangle', ['admin.reports']],
            ['admin.custom-fields', 'Custom Fields', 'layout-list', ['admin.custom-fields']],
            ['admin.user-roles', 'User Roles', 'shield', ['admin.user-roles']],
        ],
        'Keamanan' => [
            ['admin.spam-logs', 'Spam Monitor', 'shield-alert', ['admin.spam-logs']],
            ['admin.ai-security-logs', 'AI Security Logs', 'cpu', ['admin.ai-security-logs*']],
        ],
    ];
    $onSecurity = request()->routeIs('admin.ai-security-logs*');
    $offlineMode = \App\Helpers\SettingHelper::get('offline', '0');
@endphp
<body class="admin-shell h-dvh overflow-hidden antialiased font-sans text-neutral-900 lg:grid lg:grid-cols-[264px_1fr]">

    {{-- Sidebar (desktop) --}}
    <aside class="admin-side hidden lg:flex flex-col h-dvh overflow-hidden px-4 py-6 text-white">
        <div class="admin-glow" aria-hidden="true"></div>
        <a href="{{ route('admin.dashboard') }}" class="relative flex items-center gap-3 px-2 mb-8">
            <span class="grid place-items-center size-10 rounded-xl bg-[#990000] shadow-lg shadow-black/30">
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="" class="h-5 w-auto">
            </span>
            <span class="leading-tight">
                <span class="block text-[15px] font-semibold text-white">XCODE Friends</span>
                <span class="block text-xs text-zinc-400">Admin Panel</span>
            </span>
        </a>

        <nav class="admin-scroll relative flex-1 overflow-y-auto -mx-1 px-1" aria-label="Navigasi admin">
            @include('layouts.partials.admin-nav')
        </nav>

        <div class="admin-promo admin-promo-side relative mt-6">
            <span class="grid place-items-center size-11 rounded-2xl bg-white text-[#990000] shadow-lg">
                <i data-lucide="{{ $onSecurity ? 'globe' : 'shield-check' }}" class="w-5 h-5"></i>
            </span>
            <p class="mt-4 font-semibold leading-snug">{{ $onSecurity ? 'Lihat situs publik' : 'Pantau ancaman real-time' }}</p>
            <p class="mt-1 text-sm text-white/80">{{ $onSecurity ? 'Cek tampilan beranda yang dilihat anggota.' : 'Insiden dari agen AI tampil tanpa perlu refresh.' }}</p>
            <a href="{{ $onSecurity ? route('beranda') : route('admin.ai-security-logs') }}"
               class="mt-4 flex h-10 w-full items-center justify-center rounded-full bg-white text-sm font-semibold text-zinc-900 transition hover:bg-zinc-100 active:scale-[0.98]">
                {{ $onSecurity ? 'Buka Beranda' : 'Buka Security Logs' }}
            </a>
        </div>
    </aside>

    {{-- Panel konten --}}
    <main class="admin-panel relative min-w-0 h-dvh overflow-y-auto lg:h-[calc(100dvh-24px)] lg:my-3 lg:mr-3 lg:rounded-[28px]">

        {{-- Bar atas + drawer (ponsel/tablet) --}}
        <div x-data="{ open: false }" class="lg:hidden sticky top-0 z-30">
            <header class="admin-side flex h-16 items-center justify-between px-4 text-white">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="grid place-items-center size-9 rounded-lg bg-[#990000]">
                        <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="" class="h-4 w-auto">
                    </span>
                    <span class="text-sm font-semibold">Admin Panel</span>
                </a>
                <button type="button" @click="open = true" class="grid place-items-center size-10 rounded-full hover:bg-white/10" aria-label="Buka menu">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </header>
            <div x-show="open" x-cloak class="fixed inset-0 z-40" @keydown.escape.window="open = false">
                <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                <aside class="admin-side absolute inset-y-0 left-0 flex w-72 max-w-[85vw] flex-col overflow-y-auto px-4 py-5 text-white">
                    <div class="mb-6 flex items-center justify-between px-2">
                        <span class="text-sm font-semibold">Menu</span>
                        <button type="button" @click="open = false" class="grid place-items-center size-9 rounded-full hover:bg-white/10" aria-label="Tutup menu">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <nav aria-label="Navigasi admin">
                        @include('layouts.partials.admin-nav')
                    </nav>
                    <div class="mt-6 grid gap-1 border-t border-white/10 pt-4">
                        <a href="{{ route('beranda') }}" class="admin-link"><i data-lucide="home" class="w-[18px] h-[18px]"></i>Tampilan Utama</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="admin-link w-full"><i data-lucide="log-out" class="w-[18px] h-[18px]"></i>Keluar</button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>

        {{-- Topbar (desktop) --}}
        <div class="hidden lg:flex items-center justify-between gap-4 px-8 pt-6">
            <details class="admin-account relative">
                <summary class="flex items-center gap-3 rounded-full py-1 pl-1 pr-3 transition hover:bg-white">
                    <img src="{{ $adminUser->avatar_url }}" alt="" class="size-11 rounded-full object-cover bg-zinc-200"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($adminUser->username) }}&background=990000&color=fff&size=88'">
                    <span class="leading-tight">
                        <span class="block font-semibold text-zinc-900">{{ \Illuminate\Support\Str::limit($adminName, 24) }}</span>
                        <span class="block text-sm text-zinc-500">Administrator &middot; {{ '@'.$adminUser->username }}</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-500"></i>
                </summary>
                <div class="admin-pop">
                    <a href="{{ route('beranda') }}" class="admin-pop-item"><i data-lucide="home" class="w-4 h-4"></i>Tampilan Utama</a>
                    <a href="{{ route('admin.site-config') }}" class="admin-pop-item"><i data-lucide="settings" class="w-4 h-4"></i>Site Config</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-pop-item w-full text-[#990000]"><i data-lucide="log-out" class="w-4 h-4"></i>Keluar</button>
                    </form>
                </div>
            </details>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin.members') }}" method="GET" class="admin-search w-[320px]" role="search">
                    <i data-lucide="search" class="w-[18px] h-[18px] shrink-0"></i>
                    <label for="admin-top-search" class="sr-only">Cari member</label>
                    <input id="admin-top-search" name="search" placeholder="Cari member, username, atau email"
                           value="{{ request()->routeIs('admin.members') ? request('search') : '' }}">
                </form>
                <a href="{{ route('admin.ai-security-logs') }}" class="admin-round" aria-label="AI Security Logs" title="AI Security Logs">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('beranda') }}" class="admin-round" aria-label="Tampilan utama" title="Tampilan utama">
                    <i data-lucide="home" class="w-5 h-5"></i>
                </a>
            </div>
        </div>

        @if($offlineMode == '1' || $offlineMode === true)
        <div class="mx-4 mt-4 lg:mx-8 flex items-center gap-2 rounded-2xl bg-amber-100 px-4 py-3 text-sm font-semibold text-amber-900">
            <i data-lucide="construction" class="w-4 h-4 shrink-0"></i>
            <span>Mode maintenance aktif. Website sedang offline untuk pengguna publik.</span>
            <a href="{{ route('admin.site-config') }}" class="ml-auto shrink-0 text-xs underline hover:text-amber-700">Ubah Pengaturan</a>
        </div>
        @endif

        @yield('content')
    </main>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    @stack('scripts')
    
    <!-- Global Confirm Interceptor -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfigurasi SweetAlert2 bawaan untuk Tema Admin
            const swalAdmin = Swal.mixin({
                customClass: {
                    popup: 'rounded-xl shadow-2xl border border-gray-100 font-sans',
                    title: 'text-lg font-bold text-gray-900',
                    htmlContainer: 'text-sm text-gray-600',
                    actions: 'flex w-full justify-center gap-4 mt-6',
                    confirmButton: 'bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg px-5 py-2 shadow-sm transition mx-2',
                    cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg px-5 py-2 transition mx-2'
                },
                buttonsStyling: false
            });

            // Global Flash Messages Toast
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!},
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: {!! json_encode(session('error')) !!},
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Terdapat kesalahan',
                    html: `<ul style="text-align: left; margin: 0; padding-left: 1rem; font-size: 0.875rem;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>`,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            // Intercept Onclick Confirm
            document.addEventListener('click', function(e) {
                let el = e.target.closest('[onclick*="return confirm("]');
                if (el) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    let onclickAttr = el.getAttribute('onclick');
                    let match = onclickAttr.match(/confirm\(\s*['"](.*?)['"]\s*\)/);
                    let message = match ? match[1] : 'Yakin ingin melanjutkan?';
                    
                    swalAdmin.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            el.removeAttribute('onclick');
                            if(el.tagName === 'A' && el.href) {
                                window.location.href = el.href;
                            } else if(el.tagName === 'BUTTON' && el.type === 'submit' && el.form) {
                                el.form.submit();
                            } else {
                                el.click();
                            }
                            el.setAttribute('onclick', onclickAttr);
                        }
                    });
                }
            }, true);

            // Intercept Onsubmit Confirm
            document.addEventListener('submit', function(e) {
                if (e.target.hasAttribute('onsubmit')) {
                    let onsubmitAttr = e.target.getAttribute('onsubmit');
                    if (onsubmitAttr.includes('return confirm(')) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        let match = onsubmitAttr.match(/confirm\(\s*['"](.*?)['"]\s*\)/);
                        let message = match ? match[1] : 'Yakin ingin melanjutkan?';
                        
                        swalAdmin.fire({
                            title: 'Konfirmasi',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                e.target.removeAttribute('onsubmit');
                                e.target.submit();
                                e.target.setAttribute('onsubmit', onsubmitAttr);
                            }
                        });
                    }
                }
            }, true);
        });
    </script>
</body>
</html>
