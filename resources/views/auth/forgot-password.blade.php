@extends('layouts.auth')
@section('title', 'Lupa Kata Sandi')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 max-w-md mx-auto p-8 w-full relative z-10">
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-neutral-900 tracking-tight mb-2">Dapatkan kembali sandi</h2>
        <p class="text-[13px] text-neutral-500 leading-relaxed">Masukkan alamat email yang terdaftar untuk mengatur ulang kata sandi Anda.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block text-[11px] font-bold tracking-widest text-neutral-500 uppercase mb-2">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="developer@xcode.network" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-700/20 focus:border-red-700 transition-all">
            </div>
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <p class="text-[11px] text-neutral-400 mt-2 italic">- Pastikan alamat emailmu sudah terdaftar</p>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('login') }}" class="text-[13px] font-semibold text-neutral-500 hover:text-neutral-900 transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali Ke Login
            </a>
            <button type="submit" class="bg-[#990000] text-white font-bold tracking-wide px-6 py-2.5 rounded-lg text-sm hover:bg-red-800 transition-colors shadow flex items-center">
                Kirim
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </form>
</div>
@endsection