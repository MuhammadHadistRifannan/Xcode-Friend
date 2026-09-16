@extends('layouts.auth')
@section('title', 'Atur Ulang Sandi')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 w-full max-w-sm mx-auto p-8">
    <div class="text-center mb-6">
        <!-- Logo -->
        <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-16 w-auto mx-auto mb-6 object-contain hidden lg:block">
        
        <h2 class="text-2xl font-extrabold text-neutral-900 tracking-tight mb-2">Reset Password</h2>
        <p class="text-[13px] text-neutral-500 leading-relaxed">Buat password baru untuk akun Anda.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 text-sm font-medium text-[#990000] bg-red-50 border border-red-100 p-4 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">New Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <input type="password" id="new-password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-10 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                <button type="button" onclick="togglePassword('new-password', 'eye-new-pass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400 hover:text-neutral-600 transition focus:outline-none">
                    <svg id="eye-new-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Confirm New Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </span>
                <input type="password" id="confirm-password" name="password_confirmation" required placeholder="Ulangi sandi baru" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-10 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                <button type="button" onclick="togglePassword('confirm-password', 'eye-confirm-pass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400 hover:text-neutral-600 transition focus:outline-none">
                    <svg id="eye-confirm-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-bold tracking-wide rounded-lg py-3 text-sm hover:bg-red-800 transition-colors shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
            Reset Password
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
        <p class="text-sm text-neutral-500">
            <a href="{{ route('login') }}" class="font-bold text-[#990000] hover:text-red-800 transition flex items-center justify-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Login
            </a>
        </p>
    </div>
</div>
@endsection
