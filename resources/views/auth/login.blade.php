@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 w-full max-w-sm p-8 mx-auto">
    <div class="text-center mb-8">
        <!-- Logo Login -->
        <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-16 w-auto mx-auto mb-6 object-contain hidden lg:block">
        
        <h2 class="text-2xl font-extrabold text-neutral-900 tracking-tight">Welcome Back</h2>
        <p class="text-sm text-neutral-500 mt-1.5">Sign in to your account.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-600 text-xs p-3 rounded-lg mb-6 flex items-start">
            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-xs p-3 rounded-lg mb-6 flex items-start">
            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="/login" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
        @csrf
        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Username / Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>
                <input type="text" name="login" value="{{ old('login') }}" required placeholder="Username or Email" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase">Password</label>
                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-[#990000] hover:text-red-800 transition">Forgot?</a>
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-10 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                
                <!-- Tombol Hide/Unhide Password -->
                <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400 hover:text-neutral-600 transition focus:outline-none">
                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center pt-1 pb-2">
            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-[#990000] border-neutral-300 rounded focus:ring-[#990000] cursor-pointer">
            <label for="remember" class="ml-2 text-xs text-neutral-600 cursor-pointer">Remember me for 30 days</label>
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-bold tracking-wide rounded-lg py-3 text-sm hover:bg-red-800 transition-colors shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
            Log In
        </button>
    </form>

    <!-- Navigasi ke Register -->
    <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
        <p class="text-sm text-neutral-500">
            Belum memiliki akun? 
            <a href="{{ route('register') }}" class="font-bold text-[#990000] hover:text-red-800 transition">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection