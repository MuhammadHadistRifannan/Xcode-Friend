@extends('layouts.app')
@section('title', 'Welcome to XCODE-FRIENDS')

@section('content')
<div class="max-w-[95%] xl:max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- KOLOM KIRI: Login, Join Now, Recent Logins, Stats -->
    <div class="lg:col-span-3 space-y-6">

        <!-- Form Login Cepat -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h3 class="text-[11px] font-bold text-neutral-800 uppercase mb-4 tracking-wider">Login</h3>

            @if($errors->any())
                <div class="bg-red-50 text-red-600 text-[10px] p-2 rounded mb-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="/login" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-neutral-600 mb-1">Username / Email Address</label>
                    <input type="text" name="login" required placeholder="user@example.com" class="w-full bg-neutral-50 border border-neutral-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-red-700">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-neutral-600 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-neutral-50 border border-neutral-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-red-700">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-[10px] text-neutral-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-1 h-3 w-3 rounded border-neutral-300 text-red-700 focus:ring-red-600"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[10px] font-semibold text-red-700 hover:underline">Forgot password?</a>
                </div>
                <button type="submit" class="w-full bg-[#990000] text-white font-bold rounded-md py-2.5 text-xs tracking-wider hover:bg-red-800 transition">LOGIN</button>
            </form>
        </div>

        <!-- Join Now Box -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 text-center">
            <h3 class="text-sm font-bold text-neutral-800 mb-2">Join Now!</h3>
            <p class="text-[11px] text-neutral-500 mb-4 px-2">Become a part of our network to stay updated and connected.</p>
            <a href="{{ route('register') }}" class="block w-full border border-red-700 text-red-700 font-bold rounded-md py-2 text-[11px] tracking-wider hover:bg-red-50 transition">REGISTER</a>
        </div>

        <!-- Recent Logins -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h3 class="text-[11px] font-bold text-neutral-800 uppercase mb-4 tracking-wider">Recent Logins</h3>
            <div class="flex space-x-2">
                @forelse($recentLogins as $recent)
                    <img src="{{ asset('uploads/' . $recent->avatar) }}" alt="{{ $recent->username }}" class="w-8 h-8 rounded-full border border-neutral-200 object-cover" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($recent->fullname) }}&background=E5E5E5&color=333'">
                @empty
                    <p class="text-[10px] text-neutral-400">Belum ada data login.</p>
                @endforelse
            </div>
        </div>

        <!-- Network Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h3 class="text-[11px] font-bold text-neutral-800 uppercase mb-4 tracking-wider">Network Statistics</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center text-xs"><span class="text-neutral-500">Activities</span> <span class="font-bold bg-neutral-100 px-2 py-0.5 rounded">{{ number_format($stats['activities']) }}</span></div>
                <div class="flex justify-between items-center text-xs"><span class="text-neutral-500">Members</span> <span class="font-bold bg-neutral-100 px-2 py-0.5 rounded">{{ number_format($stats['members']) }}</span></div>
                <div class="flex justify-between items-center text-xs"><span class="text-neutral-500">Friendships</span> <span class="font-bold bg-neutral-100 px-2 py-0.5 rounded">{{ number_format($stats['friendships']) }}</span></div>
                <div class="flex justify-between items-center text-xs"><span class="text-neutral-500">Comments</span> <span class="font-bold bg-neutral-100 px-2 py-0.5 rounded">{{ number_format($stats['comments']) }}</span></div>
            </div>
        </div>

    </div>

    <!-- KOLOM TENGAH: Feed Komunitas -->
    <div class="lg:col-span-6 space-y-6">
        <!-- Hero Banner Image -->
        <div class="w-full h-72 md:h-96 bg-neutral-200 rounded-xl overflow-hidden shadow-sm">
            <!-- Pastikan kamu meletakkan gambar komunitas tim di public/assets/img/hero-banner.jpg -->
            <img src="{{ asset('assets/img/hero-banner.jpg') }}" alt="Community" class="w-full h-full object-cover">
        </div>

        <h3 class="text-xs font-bold text-neutral-800 uppercase border-b border-neutral-200 pb-2 tracking-wider mt-4">Community activities</h3>

        <!-- Feed Iteration -->
        @forelse($publicStreams as $stream)
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-neutral-100 flex items-center justify-center border border-neutral-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($stream->user->fullname ?? 'Unknown') }}&background=E5E5E5&color=333" class="w-full h-full rounded-full">
                </div>
                <div>
                    <p class="text-sm text-neutral-800"><span class="font-bold">{{ $stream->user->fullname ?? 'User Tidak Diketahui' }}</span> wrote a new post</p>
                    <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}</p>
                </div>
            </div>

            <div class="pl-13 mb-4">
                <p class="text-sm text-neutral-700 whitespace-pre-wrap">{{ $stream->message }}</p>
            </div>

            <div class="text-right border-t border-neutral-100 pt-3">
                <a href="{{ route('login') }}" class="text-[11px] font-bold text-red-700 hover:underline inline-flex items-center transition">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Login to comment
                </a>
            </div>
        </div>
        @empty
            <div class="text-center text-sm text-neutral-500 py-10">Belum ada aktivitas. Jadilah yang pertama membuat postingan!</div>
        @endforelse

        <div class="text-center pt-4 pb-10">
            <a href="{{ route('login') }}" class="inline-block bg-white border border-neutral-200 text-neutral-600 font-bold px-6 py-2.5 rounded-full text-[11px] tracking-wider shadow-sm hover:bg-neutral-50 transition">LOAD MORE ACTIVITIES</a>
        </div>
    </div>

    <!-- KOLOM KANAN: Review & Links -->
    <div class="lg:col-span-3 space-y-6">
        <!-- Widget Review -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 text-center">
            <h4 class="text-xs font-bold text-neutral-600 mb-2">Google Reviews</h4>
            <div class="flex justify-center text-yellow-400 mb-1">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
            <div class="text-3xl font-extrabold text-neutral-800 my-1">4.9</div>
            <a href="#" class="text-xs text-blue-600 hover:underline">532 Reviews</a>
        </div>

        <!-- Network Links -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
            <h3 class="text-xs font-bold text-neutral-800 uppercase mb-4">Network Links</h3>
            <div class="space-y-2">
                <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                    <div class="flex items-center text-sm font-medium text-neutral-600">
                        <img src="{{ asset('assets/img/logo-linkedin.png') }}" class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100 transition"> LinkedIn
                    </div>
                    <span class="text-blue-500 font-bold">&rarr;</span>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                    <div class="flex items-center text-sm font-medium text-neutral-600">
                        <img src="{{ asset('assets/img/logo-phpbb.png') }}" class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100 transition"> phpBB Group
                    </div>
                    <span class="text-blue-500 font-bold">&rarr;</span>
                </a>
                <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                    <div class="flex items-center text-sm font-medium text-neutral-600">
                        <svg class="w-4 h-4 mr-3 text-neutral-400 group-hover:text-[#1877F2] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg> Facebook
                    </div>
                    <span class="text-blue-500 font-bold">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
