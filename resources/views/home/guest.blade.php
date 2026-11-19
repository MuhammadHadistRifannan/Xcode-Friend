@extends('layouts.app')
@section('title', 'XCODE Friends · Komunitas teknologi')

@php
    $card = 'bg-white rounded-[20px] shadow-[0_1px_2px_rgba(20,20,22,0.04)]';
    $input = 'w-full h-11 bg-zinc-50 border border-zinc-200/70 rounded-xl px-3.5 text-sm text-zinc-800 placeholder-zinc-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#990000]/10 focus:border-[#990000]/60 transition';
@endphp

@section('content')
<div class="max-w-[1400px] mx-auto w-full px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-[272px_minmax(0,1fr)] xl:grid-cols-[288px_minmax(0,1fr)_304px] gap-5 items-start">

        {{-- ============ KIRI ============ --}}
        <aside class="space-y-4 lg:sticky lg:top-24 order-2 lg:order-1 animate-fade-in-up">
            <section class="{{ $card }} p-5">
                <h2 class="text-[15px] font-semibold text-zinc-900">Masuk ke akun Anda</h2>
                <p class="mt-1 text-xs text-zinc-500">Lanjutkan berbagi dengan komunitas.</p>

                <form method="POST" action="/login" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label for="guest-login" class="block text-xs font-medium text-zinc-700 mb-1.5">Username atau email</label>
                        <input id="guest-login" type="text" name="login" required autocomplete="username" placeholder="nama@contoh.com" class="{{ $input }}">
                    </div>
                    <div>
                        <label for="guest-password" class="block text-xs font-medium text-zinc-700 mb-1.5">Kata sandi</label>
                        <input id="guest-password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" class="{{ $input }}">
                    </div>
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 text-xs text-zinc-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="size-4 rounded border-zinc-300 text-[#990000] focus:ring-[#990000]/30"> Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#990000] hover:underline">Lupa sandi?</a>
                    </div>
                    <button type="submit" class="xf-pill w-full justify-center h-11 bg-[#990000] text-white font-semibold shadow-sm shadow-red-900/20 hover:bg-[#7a0000]">
                        Masuk <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-zinc-100 text-center text-xs text-zinc-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-zinc-900 hover:text-[#990000]">Daftar gratis</a>
                </div>
            </section>

            <section class="{{ $card }} p-5">
                <h3 class="xf-label mb-3">Statistik jaringan</h3>
                <dl class="grid grid-cols-2 gap-2">
                    @foreach(['activities' => 'Aktivitas', 'members' => 'Member', 'friendships' => 'Pertemanan', 'comments' => 'Komentar'] as $key => $label)
                        <div class="rounded-2xl bg-zinc-50 px-3 py-2.5">
                            <dd class="text-lg font-semibold tabular-nums text-zinc-900">{{ number_format($stats[$key]) }}</dd>
                            <dt class="text-[11px] text-zinc-500">{{ $label }}</dt>
                        </div>
                    @endforeach
                </dl>
            </section>

            <section class="{{ $card }} p-5">
                <h3 class="xf-label mb-3">Baru saja masuk</h3>
                <div class="flex -space-x-2">
                    @forelse($recentLogins as $recent)
                        <img src="{{ $recent->avatar_url }}" alt="{{ $recent->fullname ?: $recent->username }}" title="{{ $recent->fullname ?: $recent->username }}" class="size-9 rounded-full object-cover ring-2 ring-white bg-zinc-200">
                    @empty
                        <p class="text-xs text-zinc-400">Belum ada data login.</p>
                    @endforelse
                </div>
            </section>
        </aside>

        {{-- ============ TENGAH ============ --}}
        <section class="min-w-0 space-y-4 order-1 lg:order-2 animate-fade-in-up delay-100">
            {{-- Hero: gambar yang sama dengan halaman login --}}
            <div class="relative overflow-hidden rounded-[24px] bg-[#f6eef0] min-h-[300px] sm:min-h-[360px]">
                <picture>
                    <source srcset="{{ asset('assets/img/login-image.webp') }}" type="image/webp">
                    <img src="{{ asset('assets/img/login-image.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover object-left">
                </picture>
                {{-- Teks di area putih kanan gambar, tidak menutupi karakter --}}
                <div class="absolute inset-y-0 right-0 w-full sm:w-3/5 bg-gradient-to-l from-white/90 via-white/70 to-transparent sm:from-white/80 sm:via-white/50"></div>
                <div class="relative ml-auto flex min-h-[300px] sm:min-h-[360px] w-full sm:w-[40%] flex-col items-end justify-center p-6 sm:p-8 text-right">
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-[11px] font-medium text-zinc-700 ring-1 ring-zinc-900/5 backdrop-blur">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        {{ number_format($stats['members']) }} member bergabung
                    </span>
                    <h1 class="mt-3 text-2xl lg:text-[26px] xl:text-[28px] font-semibold tracking-tight leading-[1.15] text-zinc-950">Belajar, berbagi, dan tumbuh bersama <span class="text-[#990000]">XCODE.</span></h1>
                    <p class="mt-2.5 text-[13px] text-zinc-600 leading-relaxed max-w-[30ch]">Komunitas pegiat keamanan siber, developer, dan arsitek digital.</p>
                    <div class="mt-5 flex flex-wrap justify-end gap-2">
                        <a href="{{ route('login') }}" class="xf-pill h-10 px-4 bg-white text-zinc-800 ring-1 ring-zinc-900/10 hover:bg-zinc-50">Masuk</a>
                        <a href="{{ route('register') }}" class="xf-pill h-10 px-4 bg-[#990000] text-white font-semibold shadow-sm shadow-red-900/20 hover:bg-[#7a0000]">Gabung sekarang</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between px-1 pt-2">
                <h2 class="xf-label">Aktivitas komunitas</h2>
                <a href="{{ route('login') }}" class="text-xs font-medium text-zinc-500 hover:text-[#990000]">Masuk untuk ikut berdiskusi</a>
            </div>

            <div class="space-y-4">
                @forelse($publicStreams as $stream)
                    @php
                        $att = $stream->parsed_attachment;
                        $photosList = $stream->photo_urls;
                        $ptCount = count($photosList);
                        $jsonPhotos = json_encode($photosList);
                        $tile = 'w-full h-full object-cover rounded-xl cursor-pointer bg-zinc-100 transition duration-300 hover:brightness-95';
                        $onErr = "this.onerror=null; this.closest('.post-photo-wrapper')?.remove();";
                    @endphp
                    <article class="min-w-0 {{ $card }} p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $stream->user?->avatar_url ?? asset('assets/img/default.png') }}" alt="" class="size-10 rounded-full object-cover bg-zinc-100 shrink-0">
                            <div class="min-w-0">
                                <p class="text-sm leading-tight">
                                    <span class="font-semibold text-zinc-900">{{ $stream->user?->fullname ?? 'Pengguna' }}</span>
                                    <span class="text-zinc-500">
                                        @if($stream->type == 1 && !$stream->attachment) memperbarui status
                                        @elseif($stream->type == 2) mengunggah foto
                                        @elseif($stream->type == 3) membagikan video
                                        @else memposting
                                        @endif
                                    </span>
                                </p>
                                <p class="mt-0.5 text-[11px] text-zinc-400">
                                    {{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}
                                    @if($stream->app === 'group' && $stream->targetPage)
                                        &bull; di grup <a href="{{ url('/groups/' . $stream->targetPage->id) }}" class="font-medium text-zinc-600 hover:text-[#990000]">{{ $stream->targetPage->name }}</a>
                                    @elseif($stream->app === 'page' && $stream->targetPage)
                                        &bull; di page <a href="{{ url('/pages/' . $stream->targetPage->id) }}" class="font-medium text-zinc-600 hover:text-[#990000]">{{ $stream->targetPage->name }}</a>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($stream->message)
                            <p class="text-[14px] text-zinc-700 mb-4 whitespace-pre-wrap [overflow-wrap:anywhere] leading-relaxed">{{ $stream->message }}</p>
                        @endif

                        @if($ptCount > 0)
                            <div class="mb-4 post-photo-wrapper relative select-none">
                                @if($ptCount == 1)
                                    <img src="{{ $photosList[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' onerror="{{ $onErr }}" class="w-full h-auto max-h-[520px] object-cover rounded-2xl cursor-pointer bg-zinc-100" alt="Foto postingan">
                                @elseif($ptCount == 2)
                                    <div class="grid grid-cols-2 gap-1.5 h-64 sm:h-80">
                                        @foreach([0, 1] as $i)
                                            <img src="{{ $photosList[$i] }}" onclick='openLightbox({!! $jsonPhotos !!}, {{ $i }})' onerror="{{ $onErr }}" class="{{ $tile }}" alt="Foto postingan">
                                        @endforeach
                                    </div>
                                @else
                                    <div class="grid grid-cols-{{ $ptCount == 3 ? '3' : '4' }} grid-rows-2 gap-1.5 h-64 sm:h-80">
                                        <img src="{{ $photosList[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' onerror="{{ $onErr }}" class="{{ $tile }} col-span-2 row-span-2" alt="Foto postingan">
                                        <img src="{{ $photosList[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' onerror="{{ $onErr }}" class="{{ $tile }} {{ $ptCount >= 4 ? 'row-span-2' : '' }}" alt="Foto postingan">
                                        <img src="{{ $photosList[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' onerror="{{ $onErr }}" class="{{ $tile }}" alt="Foto postingan">
                                        @if($ptCount >= 4)
                                            <div class="relative w-full h-full" onclick='openLightbox({!! $jsonPhotos !!}, 3)'>
                                                <img src="{{ $photosList[3] }}" onerror="{{ $onErr }}" class="{{ $tile }}" alt="Foto postingan">
                                                @if($ptCount > 4)
                                                    <div class="absolute inset-0 rounded-xl bg-[#141416]/60 flex items-center justify-center cursor-pointer">
                                                        <span class="text-white text-2xl font-semibold tabular-nums">+{{ $ptCount - 4 }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(($stream->type == 3 || in_array($stream->app, ['video', 'videos'])) && $att)
                            @php
                                $videoUrl = $att['video_url'] ?? null;
                                $embedUrl = '';
                                if ($videoUrl && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                }
                            @endphp
                            @if($embedUrl)
                                <div class="mb-4 overflow-hidden rounded-2xl bg-zinc-900">
                                    <iframe src="{{ $embedUrl }}" class="w-full aspect-video" frameborder="0" loading="lazy" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @elseif($videoUrl)
                                <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="mb-4 flex items-center gap-3 rounded-2xl bg-zinc-50 p-3 hover:bg-zinc-100 transition">
                                    <span class="grid place-items-center size-10 rounded-xl bg-[#990000] text-white"><i data-lucide="play" class="w-4 h-4"></i></span>
                                    <span class="text-sm font-medium text-zinc-800">Tonton video</span>
                                </a>
                            @endif
                        @endif

                        @if($stream->app === 'music' && $stream->attachment)
                            <div class="mb-4 flex items-center gap-3 rounded-2xl bg-zinc-50 p-3">
                                <span class="grid place-items-center size-10 rounded-xl bg-red-50 text-[#990000] shrink-0"><i data-lucide="music" class="w-4 h-4"></i></span>
                                <audio controls class="w-full h-8" preload="none"><source src="{{ asset('storage/music/' . $stream->attachment) }}" type="audio/mpeg"></audio>
                            </div>
                        @endif

                        <div class="flex items-center gap-1 -ml-2.5">
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 h-9 px-2.5 rounded-full text-[13px] font-medium text-zinc-600 hover:bg-zinc-100 hover:text-[#990000] transition">
                                <i data-lucide="thumbs-up" class="w-4 h-4"></i> <span class="tabular-nums">{{ $stream->likes }}</span> suka
                            </a>
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 h-9 px-2.5 rounded-full text-[13px] font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 transition">
                                <i data-lucide="message-circle" class="w-4 h-4"></i> Masuk untuk berkomentar
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="{{ $card }} px-6 py-14 text-center">
                        <p class="text-sm font-semibold text-zinc-800">Belum ada aktivitas</p>
                        <p class="mt-1 text-sm text-zinc-500">Jadilah yang pertama membagikan sesuatu.</p>
                    </div>
                @endforelse

                <div class="pt-2 pb-4 text-center">
                    <a href="{{ route('login') }}" class="xf-pill bg-white text-zinc-700 shadow-sm hover:bg-zinc-50">Masuk untuk melihat lebih banyak</a>
                </div>
            </div>
        </section>

        {{-- ============ KANAN ============ --}}
        <aside class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1 content-start order-3 lg:col-span-2 xl:col-span-1 xl:sticky xl:top-24 animate-fade-in-up delay-200 [&>*]:min-w-0">
            <a href="{{ route('register') }}" class="xf-promo block group">
                <div class="relative flex items-start justify-between gap-3">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-white/70">Gabung gratis</span>
                    <span class="grid place-items-center size-7 rounded-full bg-white/10 transition group-hover:bg-white/20 group-hover:rotate-45">
                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    </span>
                </div>
                <p class="relative mt-3 text-[15px] font-semibold leading-snug">Buat akun dan mulai terhubung dengan rekan satu minat.</p>
            </a>
            <x-sidebar-right />
        </aside>
    </div>
</div>
@include('components.lightbox')
@endsection
