@php
    $activeTheme = \App\Helpers\SettingHelper::get('theme_color', 'red');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $activeTheme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - XCODE-FRIENDS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400..800&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="{{ asset('assets/img/login-image.webp') }}" type="image/webp">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Geist', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } },
        }
    </script>
    <style>
        :root {
            --brand: #990000;
            --brand-hover: #7a0000;
            --brand-soft: #fdecec;
            --auth-bg: #f8eef0;
        }
        body { background-color: var(--auth-bg); }
        /* Placeholder default Tailwind (gray-400) gagal kontras AA di atas putih. */
        input::placeholder, textarea::placeholder { color: #737373; }
        @keyframes auth-rise { from { opacity: 0; transform: translateY(16px); } }
        .auth-rise { animation: auth-rise 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards; }
        @media (prefers-reduced-motion: reduce) { .auth-rise { animation: none; } }
    </style>
    @if($activeTheme === 'dark')
    <style>
        :root, html.dark { color-scheme: dark; --auth-bg: #121212; }
        .bg-white { background-color: #1e1e1e !important; border-color: #333333 !important; color: #e5e5e5 !important; }
        .bg-gray-50, .bg-neutral-50 { background-color: #262626 !important; border-color: #383838 !important; }
        input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        select, textarea { background-color: #252525 !important; border-color: #3f3f3f !important; color: #f5f5f5 !important; }
        input:focus, select:focus, textarea:focus { background-color: #2a2a2a !important; border-color: #b71c1c !important; color: #ffffff !important; }
        input::placeholder, textarea::placeholder { color: #8a8a8a !important; }
        .text-black, .text-gray-900, .text-neutral-900, .text-gray-800, .text-neutral-800, .text-gray-700, .text-neutral-700 { color: #f5f5f5 !important; }
        .text-gray-600, .text-neutral-600, .text-gray-500, .text-neutral-500 { color: #a3a3a3 !important; }
        .border-gray-200, .border-neutral-200, .border-gray-300, .border-neutral-300 { border-color: #333333 !important; }
    </style>
    @endif
</head>
<body class="min-h-[100dvh] text-neutral-900 font-sans antialiased">

    {{-- Hero image: full-bleed on desktop, top banner on mobile. Branding is baked into the image. --}}
    <picture class="block lg:fixed lg:inset-0">
        <source srcset="{{ asset('assets/img/login-image.webp') }}" type="image/webp">
        <img src="{{ asset('assets/img/login-image.png') }}"
             alt="Anggota komunitas XCODE Friends"
             class="w-full h-56 sm:h-72 lg:h-full object-cover object-left-top">
    </picture>

    <a href="/" class="fixed top-5 right-5 z-20 inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur px-3.5 py-2 text-xs font-semibold text-neutral-700 shadow-sm ring-1 ring-black/5 transition hover:text-[var(--brand)] active:scale-[0.98]">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Beranda
    </a>

    <main class="relative z-10 flex min-h-0 lg:min-h-[100dvh] items-start lg:items-center justify-center lg:justify-end px-4 sm:px-6 lg:px-[6vw] -mt-12 sm:-mt-16 lg:mt-0 pb-10 lg:py-10">
        <div class="auth-rise w-full @yield('card_width', 'max-w-[440px]') bg-white rounded-2xl p-7 sm:p-10 shadow-[0_24px_64px_-24px_rgba(122,0,0,0.35)] ring-1 ring-black/5">
            {{-- Brand row (mirrors the reference card header) --}}
            <a href="/" class="flex items-center gap-3 w-fit mb-7">
                <span class="grid place-items-center size-11 rounded-xl bg-[var(--brand)] shadow-sm">
                    <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="" class="h-6 w-auto">
                </span>
                <span class="leading-tight">
                    <span class="block text-[17px] font-bold tracking-tight text-neutral-900">XCODE Friends</span>
                    <span class="block text-[11px] font-medium uppercase tracking-[0.12em] text-neutral-500">Community Platform</span>
                </span>
            </a>

            @yield('content')
        </div>
    </main>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>
