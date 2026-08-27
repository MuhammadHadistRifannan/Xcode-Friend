@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="bg-white rounded-xl shadow-md border border-neutral-200 w-full max-w-sm p-8">
    <div class="text-center mb-6">
        <!-- Logo Login -->
        <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-12 w-auto mx-auto mb-4 bg-neutral-900 rounded-full p-2 shadow-sm border border-neutral-200">

        <h2 class="text-xl font-bold text-neutral-900">Welcome Back</h2>
        <p class="text-xs text-neutral-500 mt-1">Sign in to your XCODE network account.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-600 text-xs p-3 rounded mb-4 text-center">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf
        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-600 uppercase mb-1">Username or Email</label>
            <div class="relative">
                <!-- User SVG -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>
                <input type="text" name="login" required placeholder="admin@xcode.network" class="w-full bg-white border border-neutral-200 rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-700 focus:border-red-700 transition">
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-[11px] font-bold tracking-wider text-neutral-600 uppercase">Password</label>
                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-red-700 hover:underline">Forgot?</a>
            </div>
            <div class="relative">
                <!-- Lock SVG -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-white border border-neutral-200 rounded-md pl-10 pr-10 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-700 focus:border-red-700 transition">
            </div>
        </div>

        <div class="flex items-center py-2">
            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-red-700 border-neutral-300 rounded focus:ring-red-600">
            <label for="remember" class="ml-2 text-xs text-neutral-600">Remember me for 30 days</label>
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-semibold rounded-md py-2.5 text-sm hover:bg-red-800 transition shadow">
            Log In &rarr;
        </button>
    </form>

    <div class="text-center mt-6">
        <span class="text-xs text-neutral-500">Don't have an account? </span>
        <a href="{{ route('register') }}" class="text-xs font-bold text-red-700 hover:underline">Request Access</a>
    </div>
</div>
@endsection
