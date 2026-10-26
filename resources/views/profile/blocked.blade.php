@extends('layouts.app')

@section('title', 'Profil Tidak Tersedia')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 bg-red-50 text-red-600 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-neutral-800 mb-2">Profil Tidak Tersedia</h2>
        <p class="text-xs sm:text-sm text-neutral-500 mb-6 leading-relaxed">
            Pengguna ini tidak dapat diakses atau Anda tidak memiliki izin untuk melihat profil ini.
        </p>
        <a href="{{ route('beranda') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white font-semibold text-xs rounded-xl shadow-sm transition">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
