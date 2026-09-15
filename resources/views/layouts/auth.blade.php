<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - XCODE-FRIENDS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> 
        body { background-color: #ffffff; } 
        
        /* Custom Animations */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.3s; }
        .stagger-3 { animation-delay: 0.5s; }
    </style>
</head>
<body class="min-h-screen bg-white text-neutral-900 font-sans antialiased flex relative">

    <!-- LEFT PANEL: BRANDING (Hidden on Mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-white border-r border-neutral-100 relative flex-col justify-center items-center p-12 overflow-hidden">
        
        <div class="relative z-10 opacity-0 animate-fade-in-up stagger-1 flex flex-col items-center text-center">
            <!-- Brand Logo -->
            <a href="/" class="flex flex-col items-center mb-8 transform hover:scale-105 transition-transform group">
                <!-- Using invert here if logo-xcode.png is white, to make it black. Or we assume it's black. Let's just use CSS filter to invert if needed. Actually we'll just show it directly -->
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-20 w-auto mb-4 filter drop-shadow-sm brightness-0">
                <span class="font-black tracking-widest uppercase text-2xl text-neutral-900">XCODE-FRIENDS</span>
            </a>

            <!-- Headline -->
            <h1 class="text-neutral-900 text-3xl xl:text-4xl font-extrabold tracking-tight leading-tight mb-4">
                Dynamic Community<br>Collaboration
            </h1>
            <p class="text-neutral-500 text-sm max-w-sm leading-relaxed">
                Connect, share, and collaborate with penetration testers and cyber security enthusiasts in our exclusive network.
            </p>
        </div>

    </div>

    <!-- RIGHT PANEL: AUTH FORM -->
    <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-6 sm:p-12 relative opacity-0 animate-fade-in-up stagger-3">
        <!-- Mobile Logo (Visible only on small screens) -->
        <div class="absolute top-6 left-6 lg:hidden">
            <a href="/" class="flex items-center space-x-2">
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-8 w-auto filter brightness-0">
            </a>
        </div>

        <div class="w-full flex justify-center">
            @yield('content')
        </div>
    </div>

    <!-- Password Toggle Script (Reusable for all auth pages) -->
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
