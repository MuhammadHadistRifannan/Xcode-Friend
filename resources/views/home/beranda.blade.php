@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-[95%] lg:max-w-5xl mx-auto w-full">
 <!-- Header Page -->
 <h2 class="text-xs font-bold text-neutral-800 uppercase tracking-widest mb-6">DASHBOARD</h2>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- KOLOM KIRI: Menu Navigasi Samping -->
        <div class="lg:col-span-3 space-y-6">

            <!-- My Apps Block -->
 <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
 <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mb-4">MY APPS</h3>
 <div class="grid grid-cols-2 gap-4">
 <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
 <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
 <span class="text-[10px] font-bold uppercase tracking-wider">FOTO</span>
 </a>
 <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
 <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
 <span class="text-[10px] font-bold uppercase tracking-wider">VIDEO</span>
 </a>
 <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
 <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
 <span class="text-[10px] font-bold uppercase tracking-wider">UNDANG</span>
 </a>
 <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
 <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
 <span class="text-[10px] font-bold uppercase tracking-wider text-center">DESAIN PROFIL</span>
 </a>
 <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
 <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
 <span class="text-[10px] font-bold uppercase tracking-wider">MY PAGES</span>
 </a>
 <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
 <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
 <span class="text-[10px] font-bold uppercase tracking-wider">GROUPS</span>
 </a>
 </div>
 </div>

 <!-- Profile Info Menu -->
 <div class="bg-white rounded-xl shadow-sm border border-neutral-200 py-3">
 <a href="{{ route('profile.show', auth()->user()->username) }}" class="flex items-center px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Profilku</span>
 </a>
 <a href="#" class="flex items-center justify-between px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
 <div class="flex items-center">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Pengikutku</span>
 </div>
 <span class="text-xs bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full">{{ $followerCount }}</span>
 </a>
 <a href="#" class="flex items-center justify-between px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
 <div class="flex items-center">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Yang aku ikuti</span>
 </div>
 <span class="text-xs bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full">{{ $followingCount }}</span>
 </a>
 <a href="#" class="flex items-center px-5 py-3 hover:bg-neutral-50 transition group">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Opsi</span>
 </a>
 </div>

 <!-- Media X-CODE (Hardcoded sesuai desain) -->
 <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
 <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mb-4">MEDIA X-CODE</h3>
 <div class="space-y-3">
 <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg> Forum X-code
 </a>
 <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> X-code Training
 </a>
 <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg> Toko X-code
 </a>
 <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Kumpulan tulisan
 </a>
 </div>
 </div>
 
 @php $leftColumnHtml = \App\Helpers\SettingHelper::get('theme_block_left_column', ''); @endphp
 @if($leftColumnHtml)
     <div class="mb-4">{!! $leftColumnHtml !!}</div>
 @endif
 </div>

    <!-- KOLOM TENGAH: BAGI CEPAT & FEED BERITA -->
    <div class="lg:col-span-6 space-y-6">

        <!-- Buat Post (Bagi Cepat) -->
 <x-feed-upload action="{{ route('stream.store') }}" app="feed" aid="0" wallId="0" />

 <!-- Feed Berita -->
 <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mt-8 mb-4">FEED BERITA</h3>

 <div id="feed-container">
    @include('home.partials._feed-items')
 </div>



 </div>

    <!-- KOLOM KANAN: Review & Links -->
    <div class="lg:col-span-3 space-y-6">
        <x-sidebar-right />
        @php $rightColumnHtml = \App\Helpers\SettingHelper::get('theme_block_right_column', ''); @endphp
        @if($rightColumnHtml)
            <div class="mb-4">{!! $rightColumnHtml !!}</div>
        @endif
    </div>

 </div>
</div>

@include('components.lightbox')

<!-- Report Modal -->
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
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const trigger = entry.target;
                const nextUrl = trigger.getAttribute('data-next-url');
                
                if (nextUrl) {
                    observer.unobserve(trigger);
                    
                    fetch(nextUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        
                        document.getElementById('feed-container').insertAdjacentHTML('beforeend', html);
                        trigger.remove();
                        
                        const newTrigger = tempDiv.querySelector('#infinite-scroll-trigger');
                        if (newTrigger) {
                            document.getElementById('feed-container').parentNode.appendChild(newTrigger);
                            observer.observe(document.getElementById('infinite-scroll-trigger'));
                        }
                    });
                }
            }
        });
    }, { rootMargin: '200px' });
    
    const trigger = document.getElementById('infinite-scroll-trigger');
    if (trigger) observer.observe(trigger);
});
</script>

@include('components.feed-scripts')
@endsection

