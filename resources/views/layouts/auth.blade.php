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
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Laser Animations */
        @keyframes laser-y {
            0% { transform: translateY(-100%); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(400%); opacity: 0; }
        }
        @keyframes laser-x {
            0% { transform: translateX(-100%); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(400%); opacity: 0; }
        }
        @keyframes pulse-laser {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; box-shadow: 0 0 30px #ff0000; }
        }
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            33% { opacity: 0.4; }
            66% { opacity: 0.8; }
        }
        
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        
        .anim-laser-y-fast { animation: laser-y 2s linear infinite; }
        .anim-laser-y-slow { animation: laser-y 5s ease-in-out infinite; }
        
        .anim-laser-x-fast { animation: laser-x 1.5s linear infinite; }
        .anim-laser-x-slow { animation: laser-x 6s ease-in-out infinite; }
        
        .anim-pulse { animation: pulse-laser 2s ease-in-out infinite; }
        .anim-pulse-slow { animation: pulse-laser 4s ease-in-out infinite; }
        .anim-flicker { animation: flicker 0.6s infinite; }
        
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.3s; }
        .stagger-3 { animation-delay: 0.5s; }
    </style>
</head>
<body class="min-h-screen bg-[#FAFAFA] text-neutral-900 font-sans antialiased flex relative">

    <!-- LEFT PANEL: BRANDING (Hidden on Mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-bl from-[#7a0000] via-[#3a0000] to-black relative flex-col justify-between p-12 overflow-hidden">
        
        <!-- GLOWING RED LASER GRID -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden mix-blend-screen">
            <!-- Glowing light leak at top right -->
            <div class="absolute -top-32 -right-32 w-[600px] h-[600px] bg-[#ff0000] opacity-30 rounded-full filter blur-[120px] anim-pulse-slow"></div>
            
            <!-- Vertical Beams Base -->
            <div class="absolute top-0 left-[15%] w-[1.5px] h-full bg-gradient-to-b from-transparent via-[#ff3333] to-transparent shadow-[0_0_15px_#ff0000] opacity-80 anim-flicker"></div>
            <div class="absolute top-0 left-[25%] w-[1px] h-full bg-[#ff0000] shadow-[0_0_10px_#ff0000] opacity-40"></div>
            <div class="absolute -top-[10%] left-[45%] w-[2px] h-[120%] bg-gradient-to-b from-transparent via-[#ff3333] to-[#ff0000] shadow-[0_0_20px_#ff0000] opacity-90 anim-pulse"></div>
            <div class="absolute top-[20%] left-[70%] w-[1px] h-[60%] bg-[#ff0000] shadow-[0_0_12px_#ff0000] opacity-60"></div>
            <div class="absolute top-[10%] left-[85%] w-[1.5px] h-[90%] bg-gradient-to-b from-[#ff3333] via-[#ff0000] to-transparent shadow-[0_0_15px_#ff0000] opacity-70"></div>
            
            <!-- Shooting Vertical Beams -->
            <div class="absolute top-0 left-[25%] w-[1px] h-full overflow-hidden opacity-80">
                <div class="w-full h-1/4 bg-white shadow-[0_0_20px_#ffffff] anim-laser-y-fast"></div>
            </div>
            <div class="absolute top-0 left-[70%] w-[1px] h-full overflow-hidden opacity-80">
                <div class="w-full h-1/3 bg-[#ffcccc] shadow-[0_0_15px_#ff0000] anim-laser-y-slow" style="animation-delay: 1.5s;"></div>
            </div>
            <div class="absolute top-0 left-[85%] w-[2px] h-full overflow-hidden opacity-100">
                <div class="w-full h-1/6 bg-white shadow-[0_0_25px_#ffffff] anim-laser-y-fast" style="animation-delay: 2.2s; animation-duration: 1.2s;"></div>
            </div>

            <!-- Horizontal Beams Base -->
            <div class="absolute top-[35%] -left-[10%] w-[120%] h-[1.5px] bg-gradient-to-r from-transparent via-[#ff3333] to-transparent shadow-[0_0_15px_#ff0000] opacity-80 anim-pulse-slow"></div>
            <div class="absolute top-[15%] left-[20%] w-[60%] h-[1px] bg-[#ff0000] shadow-[0_0_10px_#ff0000] opacity-50"></div>
            <div class="absolute top-[60%] left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-[#ff3333] to-[#ff0000] shadow-[0_0_20px_#ff0000] opacity-90 anim-flicker"></div>
            <div class="absolute top-[80%] -left-[10%] w-[70%] h-[1.5px] bg-gradient-to-r from-[#ff3333] via-[#ff0000] to-transparent shadow-[0_0_15px_#ff0000] opacity-70"></div>
            <div class="absolute top-[90%] left-[30%] w-[80%] h-[1px] bg-[#ff0000] shadow-[0_0_12px_#ff0000] opacity-50"></div>

            <!-- Shooting Horizontal Beams -->
            <div class="absolute top-[15%] left-0 w-full h-[1px] overflow-hidden opacity-90">
                <div class="w-1/4 h-full bg-white shadow-[0_0_20px_#ffffff] anim-laser-x-fast" style="animation-delay: 0.5s;"></div>
            </div>
            <div class="absolute top-[80%] left-0 w-full h-[1.5px] overflow-hidden opacity-80">
                <div class="w-1/3 h-full bg-[#ff3333] shadow-[0_0_15px_#ff0000] anim-laser-x-slow" style="animation-delay: 2s;"></div>
            </div>
            <div class="absolute top-[35%] left-0 w-full h-[2px] overflow-hidden opacity-100">
                <div class="w-[10%] h-full bg-white shadow-[0_0_30px_#ffffff] anim-laser-x-fast" style="animation-delay: 1.1s; animation-duration: 0.8s;"></div>
            </div>

            <!-- Diagonal Beams Base -->
            <div class="absolute top-1/4 left-1/4 w-[150%] h-[1px] bg-gradient-to-r from-transparent via-[#ff0000] to-transparent shadow-[0_0_10px_#ff0000] opacity-40 rotate-[35deg] origin-left"></div>
            <div class="absolute bottom-1/3 right-1/4 w-[100%] h-[1px] bg-gradient-to-r from-transparent via-[#ff0000] to-transparent shadow-[0_0_10px_#ff0000] opacity-30 -rotate-[35deg] origin-right anim-pulse"></div>
            <div class="absolute top-[40%] left-[10%] w-[120%] h-[1.5px] bg-gradient-to-r from-transparent via-[#ff3333] to-transparent shadow-[0_0_15px_#ff0000] opacity-50 rotate-[45deg] origin-left"></div>
            
            <!-- Shooting Diagonal Beams -->
            <div class="absolute top-1/4 left-1/4 w-[150%] h-[1px] overflow-hidden rotate-[35deg] origin-left">
                <div class="w-1/4 h-full bg-white shadow-[0_0_15px_#ffffff] anim-laser-x-slow" style="animation-delay: 1s;"></div>
            </div>

            <!-- Intersecting Glow Points (Crosses) -->
            <div class="absolute top-[35%] left-[45%] w-3 h-3 bg-white rounded-full shadow-[0_0_20px_#ffffff,0_0_40px_#ff0000] -translate-x-1/2 -translate-y-1/2 opacity-90 anim-pulse"></div>
            <div class="absolute top-[60%] left-[15%] w-2 h-2 bg-white rounded-full shadow-[0_0_15px_#ffffff,0_0_30px_#ff0000] -translate-x-1/2 -translate-y-1/2 opacity-70 anim-flicker"></div>
            <div class="absolute top-[80%] left-[85%] w-2 h-2 bg-[#ffcccc] rounded-full shadow-[0_0_15px_#ff3333,0_0_30px_#ff0000] -translate-x-1/2 -translate-y-1/2 opacity-80 anim-pulse-slow"></div>
            <div class="absolute top-[15%] left-[20%] w-1.5 h-1.5 bg-white rounded-full shadow-[0_0_10px_#ffffff] -translate-x-1/2 -translate-y-1/2 opacity-100 anim-pulse" style="animation-delay: 1.2s;"></div>
            <div class="absolute top-[90%] left-[30%] w-2 h-2 bg-white rounded-full shadow-[0_0_15px_#ffffff] -translate-x-1/2 -translate-y-1/2 opacity-100 anim-flicker" style="animation-delay: 0.3s;"></div>
        </div>

        <div class="relative z-10 opacity-0 animate-fade-in-up stagger-1">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center space-x-3 text-white mb-12 transform hover:scale-105 transition-transform origin-left w-fit group">
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-10 w-auto filter drop-shadow-[0_0_8px_rgba(255,255,255,0.5)] group-hover:drop-shadow-[0_0_15px_rgba(255,0,0,0.8)] transition-all">
                <span class="font-bold tracking-widest uppercase text-lg drop-shadow-[0_0_10px_rgba(255,0,0,0.8)]">XCODE-FRIENDS</span>
            </a>

            <!-- Headline -->
            <h1 class="text-white text-4xl xl:text-5xl font-extrabold tracking-tight leading-tight mb-4 drop-shadow-[0_4px_20px_rgba(255,0,0,0.5)]">
                Dynamic Community<br>Collaboration
            </h1>
            <p class="text-red-100 text-sm max-w-md leading-relaxed opacity-90">
                Connect, share, and collaborate with penetration testers and cyber security enthusiasts in our exclusive network.
            </p>
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
