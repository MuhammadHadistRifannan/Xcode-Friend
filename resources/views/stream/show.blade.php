@extends('layouts.app')

@section('title', 'Postingan oleh ' . ($stream->user?->fullname ?? $stream->user?->username ?? 'Pengguna') . ' - XCODE-FRIENDS')

@section('content')
<div class="min-h-screen bg-[#FAFAFA] py-6 sm:py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- Top Navigation / Breadcrumbs --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ url()->previous() !== url()->current() && !str_contains(url()->previous(), '/stream/') ? url()->previous() : url('/beranda') }}" 
               class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-neutral-700 hover:text-neutral-900 bg-white border border-neutral-200 px-3.5 py-2 rounded-xl shadow-sm hover:shadow transition">
                <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali</span>
            </a>

            <div class="text-xs text-neutral-500 flex items-center gap-1.5 font-medium bg-white border border-neutral-200 px-3 py-1.5 rounded-lg shadow-sm">
                @if($stream->app === 'group' && $stream->targetPage)
                    <span class="text-neutral-400">Grup:</span>
                    <a href="{{ url('/groups/' . $stream->targetPage->id) }}" class="text-red-700 font-bold hover:underline truncate max-w-[150px] sm:max-w-[200px]">
                        {{ $stream->targetPage->name }}
                    </a>
                @elseif($stream->app === 'page' && $stream->targetPage)
                    <span class="text-neutral-400">Halaman:</span>
                    <a href="{{ url('/pages/' . $stream->targetPage->id) }}" class="text-red-700 font-bold hover:underline truncate max-w-[150px] sm:max-w-[200px]">
                        {{ $stream->targetPage->name }}
                    </a>
                @elseif($stream->app === 'feed' && $stream->wall_id != $stream->uid && $stream->targetWallUser)
                    <span class="text-neutral-400">Profil:</span>
                    <a href="{{ url('/@' . $stream->targetWallUser->username) }}" class="text-red-700 font-bold hover:underline">
                        {{ $stream->targetWallUser->fullname ?? $stream->targetWallUser->username }}
                    </a>
                @else
                    <a href="{{ url('/beranda') }}" class="text-neutral-600 hover:text-red-700 hover:underline flex items-center gap-1 font-semibold">
                        <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Feed Beranda
                    </a>
                @endif
            </div>
        </div>

        {{-- Hidden Stream Warning Banner (for Admin) --}}
        @if($stream->hide == 1)
            <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs sm:text-sm flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <strong class="font-bold">Postingan Tersembunyi (Hide = 1)</strong>
                    <p class="mt-0.5 text-xs text-amber-700">Postingan ini disembunyikan dari feed umum atau merupakan entri lampau. Anda dapat melihatnya karena memiliki hak akses Administrator.</p>
                </div>
            </div>
        @endif

        {{-- Single Post Card --}}
        <div class="space-y-4">
            <x-single-stream :stream="$stream" />
        </div>

    </div>
</div>

{{-- Report Modal --}}
<div id="reportModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">Laporkan Konten</h3>
            <button type="button" onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <input type="hidden" name="url" id="reportUrl" value="">
            <div class="p-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pelaporan</label>
                <textarea name="message" rows="4" class="w-full text-sm p-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 resize-none mb-3" placeholder="Jelaskan mengapa konten ini tidak pantas..." required></textarea>
                <p class="text-xs text-gray-500">Laporan Anda bersifat anonim dan akan ditinjau oleh administrator.</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-bold text-white hover:bg-red-700 shadow-sm transition-colors">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReportModal(url) {
        document.getElementById('reportUrl').value = url;
        document.getElementById('reportModal').classList.remove('hidden');
    }
    
    function closeReportModal() {
        document.getElementById('reportModal').classList.add('hidden');
    }

    // Membuka kolom komentar secara default di halaman detail tunggal
    document.addEventListener('DOMContentLoaded', function() {
        const commentBox = document.getElementById('comment-form-home-{{ $stream->id }}');
        if (commentBox) {
            commentBox.classList.remove('hidden');
        }
    });
</script>

@include('components.feed-scripts')
@endsection
