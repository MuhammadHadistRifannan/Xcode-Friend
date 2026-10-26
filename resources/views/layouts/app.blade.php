@php
    $activeTheme = \App\Helpers\SettingHelper::get('theme_color', 'red');
    $headerCode = \App\Helpers\SettingHelper::get('theme_block_header_code', '');
    $footerCode = \App\Helpers\SettingHelper::get('theme_block_footer_code', '');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $activeTheme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'XCODE-FRIENDS')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Montserrat (Gotham Bold alternative) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800;900&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if($activeTheme === 'dark')
    <style>
        :root, html.dark {
            color-scheme: dark;
        }

        /* ── Base & Page Backgrounds ── */
        body, main {
            background-color: #121212 !important;
            color: #e5e5e5 !important;
        }
        :not(.no-theme).bg-\[\#fafafa\],
        :not(.no-theme).bg-\[\#FAFAFA\],
        :not(.no-theme).bg-\[\#f5f5f5\],
        :not(.no-theme)[class*="bg-[#fafafa]"],
        :not(.no-theme)[class*="bg-[#FAFAFA]"],
        :not(.no-theme)[class*="bg-[#f5f5f5]"] {
            background-color: #121212 !important;
        }

        /* ── Cards & Primary Containers ── */
        :not(.no-theme).bg-white,
        :not(.no-theme).bg-\[\#f9f9f9\],
        :not(.no-theme).bg-\[\#fcfcfc\],
        :not(.no-theme).bg-\[\#f6f3f3\],
        :not(.no-theme)[class*="bg-[#f9f9f9]"],
        :not(.no-theme)[class*="bg-[#fcfcfc]"],
        :not(.no-theme)[class*="bg-[#f6f3f3]"] {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
            color: #e5e5e5 !important;
        }

        /* ── Secondary Surfaces & Subtle Backgrounds ── */
        :not(.no-theme).bg-gray-50,
        :not(.no-theme).bg-neutral-50,
        :not(.no-theme).bg-slate-50,
        :not(.no-theme).bg-\[\#f8f9fa\],
        :not(.no-theme)[class*="bg-[#f8f9fa]"] {
            background-color: #262626 !important;
            border-color: #383838 !important;
        }
        :not(.no-theme).bg-gray-100,
        :not(.no-theme).bg-neutral-100,
        :not(.no-theme).bg-slate-100 {
            background-color: #2a2a2a !important;
            border-color: #383838 !important;
        }
        :not(.no-theme).bg-gray-200,
        :not(.no-theme).bg-neutral-200,
        :not(.no-theme).bg-slate-200 {
            background-color: #333333 !important;
            border-color: #444444 !important;
        }
        :not(.no-theme).bg-\[\#f0f5fa\],
        :not(.no-theme)[class*="bg-[#f0f5fa]"] {
            background-color: #1e293b !important;
        }
        :not(.no-theme).bg-\[\#f4dada\],
        :not(.no-theme)[class*="bg-[#f4dada]"] {
            background-color: #381a1a !important;
            color: #fca5a5 !important;
        }

        /* ── Form Controls (Inputs, Selects, Textareas) ── */
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
            --tw-ring-color: rgba(183, 28, 28, 0.3) !important;
        }
        :not(.no-theme) input::placeholder,
        :not(.no-theme) textarea::placeholder {
            color: #737373 !important;
        }
        :not(.no-theme) select option {
            background-color: #252525 !important;
            color: #f5f5f5 !important;
        }

        /* ── Text Colors: Black / Dark Grays -> Light ── */
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
        :not(.no-theme)[class*="text-[#333333]"],
        :not(.no-theme).text-\[\#495057\],
        :not(.no-theme)[class*="text-[#495057]"] {
            color: #f5f5f5 !important;
        }

        /* ── Text Colors: Muted / Secondary ── */
        :not(.no-theme).text-gray-600,
        :not(.no-theme).text-neutral-600,
        :not(.no-theme).text-slate-600,
        :not(.no-theme).text-gray-500,
        :not(.no-theme).text-neutral-500,
        :not(.no-theme).text-slate-500,
        :not(.no-theme).text-\[\#666666\],
        :not(.no-theme)[class*="text-[#666666]"] {
            color: #a3a3a3 !important;
        }

        /* ── Text Colors: Subtle / Tertiary ── */
        :not(.no-theme).text-gray-400,
        :not(.no-theme).text-neutral-400,
        :not(.no-theme).text-slate-400,
        :not(.no-theme).text-\[\#888888\],
        :not(.no-theme)[class*="text-[#888888]"] {
            color: #808080 !important;
        }

        /* ── Default Headings & Labels ── */
        h1:not(.no-theme), h2:not(.no-theme), h3:not(.no-theme), h4:not(.no-theme), h5:not(.no-theme), h6:not(.no-theme) {
            color: #f5f5f5;
        }
        label:not(.no-theme) {
            color: #e5e5e5;
        }

        /* ── Borders & Dividers ── */
        :not(.no-theme).border-gray-100,
        :not(.no-theme).border-gray-200,
        :not(.no-theme).border-gray-300,
        :not(.no-theme).border-neutral-100,
        :not(.no-theme).border-neutral-200,
        :not(.no-theme).border-neutral-300,
        :not(.no-theme).border-slate-100,
        :not(.no-theme).border-slate-200,
        :not(.no-theme).border-slate-300,
        :not(.no-theme).border-\[\#e5e5e5\],
        :not(.no-theme).border-\[\#eee\],
        :not(.no-theme).border-\[\#e5e7eb\],
        :not(.no-theme)[class*="border-[#e5e5e5]"] {
            border-color: #333333 !important;
        }
        :not(.no-theme).divide-gray-100 > *,
        :not(.no-theme).divide-gray-200 > *,
        :not(.no-theme).divide-neutral-100 > *,
        :not(.no-theme).divide-neutral-200 > * {
            border-color: #333333 !important;
        }

        /* ── Hover States ── */
        :not(.no-theme).hover\:bg-gray-50:hover,
        :not(.no-theme).hover\:bg-neutral-50:hover,
        :not(.no-theme).hover\:bg-slate-50:hover {
            background-color: #2a2a2a !important;
        }
        :not(.no-theme).hover\:bg-gray-100:hover,
        :not(.no-theme).hover\:bg-neutral-100:hover,
        :not(.no-theme).hover\:bg-slate-100:hover {
            background-color: #333333 !important;
        }
        :not(.no-theme).hover\:bg-gray-200:hover,
        :not(.no-theme).hover\:bg-neutral-200:hover {
            background-color: #383838 !important;
        }
        :not(.no-theme).hover\:text-gray-800:hover,
        :not(.no-theme).hover\:text-gray-700:hover,
        :not(.no-theme).hover\:text-neutral-800:hover,
        :not(.no-theme).hover\:text-neutral-700:hover {
            color: #ffffff !important;
        }
        :not(.no-theme).hover\:bg-\[\#f4dada\]:hover,
        :not(.no-theme)[class*="hover:bg-[#f4dada]"]:hover {
            background-color: #451a1a !important;
            color: #fca5a5 !important;
        }
        :not(.no-theme).hover\:bg-red-50:hover,
        :not(.no-theme)[class*="hover:bg-red-50"]:hover {
            background-color: #381818 !important;
        }

        /* ── Telusur Filter & Dropdown ── */
        #lokasiDropdown {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
        }
        #lokasiDropdown > div:first-child {
            background-color: #1e1e1e !important;
            border-color: #333333 !important;
        }
        #lokasiSearch {
            background-color: #262626 !important;
            color: #f5f5f5 !important;
            border: 1px solid #3f3f3f !important;
            border-radius: 8px;
            padding: 4px 8px;
        }
        .lokasi-option {
            color: #d4d4d4 !important;
        }
        .lokasi-option:hover {
            background-color: #381a1a !important;
            color: #fca5a5 !important;
        }
        .lokasi-option.bg-\[\#f4dada\],
        .lokasi-option[class*="bg-[#f4dada]"] {
            background-color: #451a1a !important;
            color: #fca5a5 !important;
        }

        /* ── Pagination ── */
        nav[role="navigation"] a,
        nav[role="navigation"] span {
            background-color: #252525 !important;
            border-color: #383838 !important;
            color: #d4d4d4 !important;
        }
        nav[role="navigation"] a:hover {
            background-color: #333333 !important;
            color: #ffffff !important;
        }
        nav[role="navigation"] span[aria-current="page"] span {
            background-color: #b71c1c !important;
            border-color: #b71c1c !important;
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
    <style> body { background-color: #FAFAFA; } </style>
    @endif
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #d4d4d8;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #a1a1aa;
        }
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #d4d4d8 transparent;
        }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #525252;
        }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #737373;
        }
        html.dark .custom-scrollbar {
            scrollbar-color: #525252 transparent;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen antialiased text-neutral-900">

    {!! $headerCode !!}

    <!-- Memanggil Navbar -->
    @include('layouts.partials.navbar')

    @auth
        @if((int) auth()->user()->disabled === 1)
            @php
                $pendingLimit = (int) \App\Helpers\SettingHelper::get('pending_post_limit', 5);
                $todayStart = strtotime('today midnight');
                $todayPostsCount = \Illuminate\Support\Facades\DB::table('jcow_streams')
                    ->where('uid', auth()->id())
                    ->where('created', '>=', $todayStart)
                    ->count();
                $remainingPosts = max(0, $pendingLimit - $todayPostsCount);
            @endphp
            <div class="bg-amber-500/10 border-b border-amber-300 dark:border-amber-600/40 py-2.5 px-4 shadow-sm relative z-20">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 text-xs sm:text-sm text-amber-900 dark:text-amber-200">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500 text-white shrink-0 font-black text-xs shadow-sm">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        </span>
                        <div>
                            <span class="font-bold uppercase tracking-wider text-amber-950 dark:text-amber-100">Status Akun: Menunggu Persetujuan (Pending)</span>
                            <span class="text-amber-800 dark:text-amber-300 block sm:inline sm:ml-1">
                                Akun Anda dalam peninjauan oleh Administrator. Beberapa fitur dibatasi (maksimal <strong>{{ $pendingLimit }}</strong> postingan/hari, sisa hari ini: <strong>{{ $remainingPosts }}</strong> kiriman).
                            </span>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-amber-200/80 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 px-2.5 py-0.5 rounded-full border border-amber-300/80 shrink-0">
                        <i data-lucide="shield-alert" class="w-3 h-3"></i> Akses Terbatas
                    </span>
                </div>
            </div>
        @endif
    @endauth

    <!-- Konten Utama -->
    <main class="flex-grow w-full py-10 relative">
        @yield('content')
    </main>

    <!-- Memanggil Footer -->
    @include('layouts.partials.footer')

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>

    @auth
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@7/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
    <script>
        var _isSecure = window.location.protocol === 'https:';
        var _wsPort = window.location.port ? parseInt(window.location.port) : (_isSecure ? 443 : 80);
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config("broadcasting.connections.reverb.key") }}',
            wsHost: window.location.hostname,
            wsPort: _wsPort,
            wssPort: _wsPort,
            forceTLS: _isSecure,
            enabledTransports: ['ws', 'wss'],
        });

        // Base URL dinamis untuk asset (storage, dll)
        window.AppBase = window.location.origin;

        // ==== Realtime online presence (WebSocket) ====
        window.onlineUsers = {};
        window.onlineUsersReady = false;

        function applyPresence(users) {
            users.forEach(function(u) {
                window.onlineUsers[u.id] = u;
            });
        }

        window.Echo.join('online')
            .here(function(users) {
                window.onlineUsers = {};
                applyPresence(users);
                window.onlineUsersReady = true;
                window.dispatchEvent(new CustomEvent('online-update', { detail: { id: null, online: false } }));
            })
            .joining(function(user) {
                window.onlineUsers[user.id] = user;
                window.dispatchEvent(new CustomEvent('online-update', { detail: { id: user.id, online: true } }));
            })
            .leaving(function(user) {
                delete window.onlineUsers[user.id];
                window.dispatchEvent(new CustomEvent('online-update', { detail: { id: user.id, online: false } }));
            });

        // ==== Update last_seen on connect (server-side presence marker) ====
        window.Echo.connector.pusher.connection.bind('connected', function() {
            navigator.sendBeacon('{{ route("presence.update") }}', new URLSearchParams({
                status: 'online',
                _token: '{{ csrf_token() }}'
            }).toString());
        });

        // Heartbeat berkala setiap 60 detik untuk menjaga last_seen aktif saat tab terbuka
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                navigator.sendBeacon('{{ route("presence.update") }}', new URLSearchParams({
                    status: 'online',
                    _token: '{{ csrf_token() }}'
                }).toString());
            }
        }, 60000);

        window.addEventListener('pagehide', function() {
            navigator.sendBeacon('{{ route("presence.update") }}', new URLSearchParams({
                status: 'offline',
                _token: '{{ csrf_token() }}'
            }).toString());
        });
    </script>
    @endauth

    @stack('scripts')
    
    <!-- Global Confirm Interceptor -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfigurasi default SweetAlert2 agar sesuai tema
            const swalTheme = Swal.mixin({
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-neutral-100 font-sans',
                    title: 'text-lg font-bold text-neutral-900',
                    htmlContainer: 'text-sm text-neutral-600',
                    actions: 'flex w-full justify-center gap-4 mt-6',
                    confirmButton: 'bg-red-700 hover:bg-red-800 text-white font-bold rounded-xl px-6 py-2.5 shadow-md transition mx-2',
                    cancelButton: 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-medium rounded-xl px-6 py-2.5 transition mx-2'
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
                    title: 'Terdapat kesalahan pada input',
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

            // Intercept onclick dan onsubmit
            document.addEventListener('click', function(e) {
                // Cari elemen yang punya atribut onclick berisi confirm(
                let el = e.target.closest('[onclick*="return confirm("]');
                if (el) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    let onclickAttr = el.getAttribute('onclick');
                    let match = onclickAttr.match(/confirm\(\s*['"](.*?)['"]\s*\)/);
                    let message = match ? match[1] : 'Apakah Anda yakin?';
                    
                    swalTheme.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Eksekusi aksi aslinya tanpa confirm
                            el.removeAttribute('onclick');
                            if(el.tagName === 'A' && el.href) {
                                window.location.href = el.href;
                            } else if(el.tagName === 'BUTTON' && el.type === 'submit' && el.form) {
                                el.form.submit();
                            } else {
                                el.click();
                            }
                            // Kembalikan atribut untuk pemakaian selanjutnya (meski page biasa reload)
                            el.setAttribute('onclick', onclickAttr);
                        }
                    });
                }
            }, true);

            document.addEventListener('submit', function(e) {
                if (e.target.hasAttribute('onsubmit')) {
                    let onsubmitAttr = e.target.getAttribute('onsubmit');
                    if (onsubmitAttr.includes('return confirm(')) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        let match = onsubmitAttr.match(/confirm\(\s*['"](.*?)['"]\s*\)/);
                        let message = match ? match[1] : 'Apakah Anda yakin?';
                        
                        swalTheme.fire({
                            title: 'Konfirmasi',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Lanjutkan',
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

    {!! $footerCode !!}
</body>
</html>
