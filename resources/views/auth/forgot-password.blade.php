@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 max-w-md mx-auto p-8 w-full">
    <div class="flex flex-col items-center text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-50 text-red-700 mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
        </div>
        <h2 class="text-2xl font-extrabold text-neutral-900 tracking-tight mb-2">Lupa Kata Sandi?</h2>
        <p class="text-sm text-neutral-500">Masukkan alamat email yang terdaftar, kami akan mengirimkan tautan untuk mengatur ulang sandi Anda.</p>
    </div>

    <form method="POST" action="#" class="space-y-6">
        @csrf
        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <input type="email" required placeholder="developer@xcode.network" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-700/20 focus:border-red-700 transition-all">
            </div>
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-bold tracking-wide px-6 py-3 rounded-lg text-sm hover:bg-red-800 transition-colors shadow-sm">
            Kirim Tautan Reset
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="text-sm font-semibold text-neutral-500 hover:text-neutral-900 transition flex items-center justify-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Login
        </a>
    </div>
</div>
@endsection