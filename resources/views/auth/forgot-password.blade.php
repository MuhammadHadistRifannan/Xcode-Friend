@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('content')
<div class="bg-white rounded-xl shadow-md border border-neutral-200 max-w-lg mx-auto p-8 w-full">
    <div class="flex flex-col items-center text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-700 mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
        </div>
        <h2 class="text-xl font-bold text-neutral-900 mb-2">Dapatkan kembali sandi</h2>
        <p class="text-sm text-neutral-500">Masukkan alamat email yang terdaftar untuk mengatur ulang kata sandi Anda.</p>
    </div>

    <form method="POST" action="#" class="space-y-5">
        @csrf
        <div>
            <label class="block text-[11px] font-bold tracking-wider text-neutral-600 uppercase mb-1">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <input type="email" required placeholder="developer@xcode.com" class="w-full bg-white border border-neutral-200 rounded-md pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-700 focus:border-red-700 transition">
            </div>
            <span class="text-[11px] text-neutral-400 mt-1 block">Pastikan alamat emailmu telah terdaftar</span>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-neutral-500 hover:text-neutral-900 transition">&larr; Kembali ke Login</a>
            <button type="submit" class="bg-[#990000] text-white font-semibold px-6 py-2.5 rounded-md text-sm hover:bg-red-800 transition shadow">
                Ajukan &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
