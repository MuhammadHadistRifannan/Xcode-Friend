@extends('layouts.auth')
@section('title', 'Lupa Kata Sandi')
@section('content')
<div>
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Lupa kata sandi?</h1>
        <p class="mt-1.5 text-sm text-neutral-600">Masukkan email terdaftar. Kami akan mengirim tautan untuk mengatur ulang kata sandi.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 p-4 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="block font-bold mb-0.5">Cek email Anda</strong>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Memproses...';">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-neutral-800 mb-2">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="developer@xcode.network" class="w-full bg-white border border-neutral-300 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-[#990000]/10 focus:border-[#990000] transition-all">
            </div>
            @error('email')
                <p class="text-[11px] text-[#990000] mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-semibold rounded-lg py-3.5 text-sm hover:bg-[#7a0000] active:scale-[0.99] transition-colors shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
            Kirim Tautan Reset
        </button>
    </form>

    <div class="mt-7 pt-6 border-t border-neutral-200 text-center">
        <p class="text-sm text-neutral-600">
            Ingat kata sandi Anda?
            <a href="{{ route('login') }}" class="font-semibold text-[#990000] hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection