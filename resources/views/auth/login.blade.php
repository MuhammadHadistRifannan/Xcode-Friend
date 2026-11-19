@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<h1 class="text-2xl font-bold tracking-tight text-neutral-900">Masuk ke akun Anda</h1>
<p class="mt-1.5 text-sm text-neutral-600">Silakan masukkan kredensial Anda untuk melanjutkan ke komunitas XCODE.</p>

@if($errors->any())
    <div role="alert" class="mt-6 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

@if(session('success'))
    <div role="status" class="mt-6 flex items-start gap-2 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800">
        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

<form method="POST" action="/login" class="mt-7 grid gap-5" onsubmit="const b=this.querySelector('button[type=submit]'); b.disabled=true; b.firstElementChild.textContent='Memproses...';">
    @csrf
    <div class="grid gap-2">
        <label for="login" class="text-sm font-semibold text-neutral-800">Username / Email</label>
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </span>
            <input id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="username" autofocus
                   placeholder="nama@email.com atau username"
                   class="w-full rounded-lg border border-neutral-300 bg-white py-3 pl-11 pr-4 text-sm text-neutral-900 placeholder:text-neutral-500 transition focus:border-[var(--brand)] focus:outline-none focus:ring-4 focus:ring-[var(--brand)]/10">
        </div>
    </div>

    <div class="grid gap-2">
        <div class="flex items-center justify-between">
            <label for="password" class="text-sm font-semibold text-neutral-800">Kata Sandi</label>
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-[var(--brand)] hover:underline">Lupa kata sandi?</a>
        </div>
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </span>
            <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                   class="w-full rounded-lg border border-neutral-300 bg-white py-3 pl-11 pr-11 text-sm text-neutral-900 placeholder:text-neutral-500 transition focus:border-[var(--brand)] focus:outline-none focus:ring-4 focus:ring-[var(--brand)]/10">
            <button type="button" onclick="togglePassword('password', 'eye-icon')" aria-label="Tampilkan kata sandi"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-neutral-500 transition hover:text-neutral-800">
                <svg id="eye-icon" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
    </div>

    <label class="flex w-fit cursor-pointer items-center gap-2.5 text-sm text-neutral-700">
        <input type="checkbox" name="remember" class="h-4 w-4 cursor-pointer rounded border-neutral-300 text-[var(--brand)] focus:ring-[var(--brand)]">
        Ingat saya
    </label>

    <button type="submit" class="group mt-1 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[var(--brand)] py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[var(--brand-hover)] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-70">
        <span>Masuk Sekarang</span>
        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
    </button>
</form>

<p class="mt-7 border-t border-neutral-200 pt-6 text-center text-sm text-neutral-600">
    Belum memiliki akun?
    <a href="{{ route('register') }}" class="font-semibold text-[var(--brand)] hover:underline">Daftar sekarang</a>
</p>
@endsection
