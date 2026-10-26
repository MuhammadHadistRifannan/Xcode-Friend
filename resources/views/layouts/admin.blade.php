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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
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
<body class="flex flex-col h-screen overflow-hidden antialiased text-neutral-900">



    <!-- Layout Admin -->
    <div class="flex-grow flex w-full h-full overflow-hidden">
        
        <!-- Sidebar Management Tools -->
        <aside class="w-64 bg-white border-r border-gray-200 h-full overflow-hidden hidden md:flex flex-col flex-shrink-0">
            <!-- Header Fixed -->
            <div class="p-6 pb-4 border-b border-gray-100/50">
                <div class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Management Tools</div>
            </div>
            
            <!-- Scrollable Nav -->
            <div class="px-6 py-4 flex-grow overflow-y-auto">
                <nav class="flex flex-col gap-1">
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard', 'admin.index') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard', 'admin.index') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.site-config') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.site-config') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="settings" class="w-4 h-4 {{ request()->routeIs('admin.site-config') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Site Config
                    </a>

                    <a href="{{ route('admin.modules') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.modules') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="box" class="w-4 h-4 {{ request()->routeIs('admin.modules') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Modules
                    </a>


                    
                    <a href="{{ route('admin.themes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.themes') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="palette" class="w-4 h-4 {{ request()->routeIs('admin.themes') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Themes
                    </a>

                    <a href="{{ route('admin.themes.blocks') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.themes.blocks') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="layout-template" class="w-4 h-4 {{ request()->routeIs('admin.themes.blocks') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Manage Blocks
                    </a>

                    <a href="{{ route('admin.members') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.members', 'admin.members.show') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('admin.members', 'admin.members.show') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Members
                    </a>

                    <a href="{{ route('admin.stream-monitor') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.stream-monitor') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="activity" class="w-4 h-4 {{ request()->routeIs('admin.stream-monitor') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Stream Monitor
                    </a>

                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.reports') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="alert-triangle" class="w-4 h-4 {{ request()->routeIs('admin.reports') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Reports
                    </a>

                    <a href="{{ route('admin.custom-fields') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.custom-fields') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="layout-list" class="w-4 h-4 {{ request()->routeIs('admin.custom-fields') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Custom Fields
                    </a>

                    <a href="{{ route('admin.user-roles') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.user-roles') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="shield" class="w-4 h-4 {{ request()->routeIs('admin.user-roles') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        User Roles
                    </a>

                    <a href="{{ route('admin.spam-logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.spam-logs') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="shield-alert" class="w-4 h-4 {{ request()->routeIs('admin.spam-logs') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Spam Monitor
                    </a>
                </nav>
            </div>

            <div class="p-6 border-t border-gray-200">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('beranda') }}" class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        Tampilan Utama
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors bg-red-50 text-red-600 hover:bg-red-100">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Konten Utama Admin -->
        <main class="flex-grow relative h-full overflow-y-auto bg-[#f5f5f5]">
            @php
                $offlineMode = \App\Helpers\SettingHelper::get('offline', '0');
            @endphp
            @if($offlineMode == '1' || $offlineMode === true)
            <div class="bg-amber-400 text-amber-900 text-sm font-bold px-5 py-2.5 flex items-center gap-2 border-b border-amber-500">
                <i data-lucide="construction" class="w-4 h-4 shrink-0"></i>
                <span>⚠️ MODE MAINTENANCE AKTIF — Website sedang dalam kondisi offline untuk pengguna publik.</span>
                <a href="{{ route('admin.site-config') }}" class="ml-auto text-xs underline hover:text-amber-800 transition shrink-0">Ubah Pengaturan →</a>
            </div>
            @endif
            @yield('content')


        </main>
    </div>

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
