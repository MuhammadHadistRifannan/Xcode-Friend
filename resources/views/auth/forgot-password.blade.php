@extends('layouts.auth')
@section('title', 'Lupa Kata Sandi')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 w-full max-w-sm mx-auto p-8">
    <div class="text-center mb-6">
        <!-- Logo -->
        <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-16 w-auto mx-auto mb-6 object-contain hidden lg:block">
        
        <h2 class="text-2xl font-extrabold text-neutral-900 tracking-tight mb-2">Forgot Password?</h2>
        <p class="text-[13px] text-neutral-500 leading-relaxed">Masukkan email yang terdaftar dan kami akan mengirimkan instruksi untuk mengatur ulang password.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 p-4 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="block font-bold mb-0.5">Check your email</strong>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
        @csrf
        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-2">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="developer@xcode.network" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
            </div>
            @error('email')
                <p class="text-[11px] text-[#990000] mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-bold tracking-wide rounded-lg py-3 text-sm hover:bg-red-800 transition-colors shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
            Send Reset Link
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
        <p class="text-sm text-neutral-500">
            Remember your password? 
            <a href="{{ route('login') }}" class="font-bold text-[#990000] hover:text-red-800 transition">Login</a>
        </p>
    </div>
</div>
@endsection