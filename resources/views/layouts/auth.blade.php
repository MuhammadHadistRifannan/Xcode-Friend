<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - XCODE-FRIENDS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> 
        body { background-color: #FAFAFA; } 
        
        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
            50% { transform: translateY(-20px) rotate(-3deg) scale(1.05); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.3s; }
        .stagger-3 { animation-delay: 0.5s; }
    </style>
</head>
<body class="min-h-screen bg-[#FAFAFA] text-neutral-900 font-sans antialiased flex relative">

    <!-- LEFT PANEL: BRANDING (Hidden on Mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#990000] relative flex-col justify-between p-12 overflow-hidden">
        <!-- Decorative subtle background elements -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-red-600/20 via-transparent to-transparent z-0"></div>
        <div class="absolute -top-[10%] -left-[10%] w-[50%] pt-[50%] bg-red-700/30 rounded-full mix-blend-multiply filter blur-[80px] animate-float opacity-70"></div>
        <div class="absolute top-[20%] right-[10%] w-[30%] pt-[30%] bg-red-500/20 rounded-full mix-blend-overlay filter blur-[60px] animate-float-slow opacity-60"></div>
        <div class="absolute bottom-[20%] right-[20%] w-[40%] pt-[40%] bg-black/10 rounded-full mix-blend-multiply filter blur-[100px] animate-float opacity-50" style="animation-delay: 2s;"></div>

        <div class="relative z-10 opacity-0 animate-fade-in-up stagger-1">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center space-x-3 text-white mb-12 transform hover:scale-105 transition-transform origin-left w-fit">
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-10 w-auto filter drop-shadow-md">
                <span class="font-bold tracking-widest uppercase text-lg drop-shadow">XCODE-FRIENDS</span>
            </a>

            <!-- Headline -->
            <h1 class="text-white text-4xl xl:text-5xl font-extrabold tracking-tight leading-tight mb-4 drop-shadow-sm">
                Dynamic Community<br>Collaboration
            </h1>
            <p class="text-red-100 text-sm max-w-md leading-relaxed opacity-90">
                Connect, share, and collaborate with penetration testers and cyber security enthusiasts in our exclusive network.
            </p>
        </div>

        <!-- Corner Image with subtle floating animation -->
        <div class="absolute bottom-0 left-0 w-[85%] max-w-2xl z-10 pointer-events-none opacity-0 animate-fade-in-up stagger-2">
            <img src="{{ asset('assets/img/corner-auth.png') }}" alt="Decoration" class="w-full h-auto object-contain animate-float drop-shadow-2xl">
        </div>
    </div>

    <!-- RIGHT PANEL: AUTH FORM -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative opacity-0 animate-fade-in-up stagger-3">
        <!-- Mobile Logo (Visible only on small screens) -->
        <div class="absolute top-6 left-6 lg:hidden">
            <a href="/" class="flex items-center space-x-2">
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-8 w-auto filter drop-shadow-sm invert opacity-80">
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
