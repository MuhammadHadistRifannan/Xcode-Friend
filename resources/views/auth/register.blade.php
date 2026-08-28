@extends('layouts.auth')
@section('title', 'Register')
@section('content')
<div class="w-full max-w-2xl bg-white shadow-sm border border-neutral-200 rounded-2xl p-8 sm:p-12 my-8">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-neutral-900 tracking-tight">Create an Account</h2>
        <p class="text-sm text-neutral-500 mt-2">Bergabunglah dengan ekosistem XCODE Technical Network.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 p-4 mb-8 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">Periksa kembali data Anda:</h3>
            </div>
            <ul class="list-disc list-inside text-[11px] text-red-600 ml-6 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register" class="space-y-10">
        @csrf

        <!-- Passport Section -->
        <div>
            <h3 class="text-sm font-bold text-neutral-900 mb-5 flex items-center border-b border-neutral-100 pb-3">
                <svg class="w-4 h-4 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Informasi Login
            </h3>
            <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-6">
                <div>
                    <label class="block text-xs font-bold text-neutral-600 mb-1.5">Email Address <span class="text-red-600">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-neutral-600 mb-1.5">Username <span class="text-red-600">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Tanpa spasi" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-neutral-600 mb-1.5">Password <span class="text-red-600">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- Personal Info Section -->
        <div>
            <h3 class="text-sm font-bold text-neutral-900 mb-5 flex items-center border-b border-neutral-100 pb-3">
                <svg class="w-4 h-4 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Data Pribadi
            </h3>
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-neutral-600 mb-1.5">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-neutral-600 mb-1.5">Tanggal Lahir <span class="text-red-600">*</span></label>
                        <div class="flex gap-2">
                            <select name="birthday" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-2 text-xs outline-none">
                                @for($i=1; $i<=31; $i++) <option value="{{$i}}" {{ old('birthday') == $i ? 'selected' : '' }}>{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option> @endfor
                            </select>
                            <select name="birthmonth" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-2 text-xs outline-none">
                                @for($i=1; $i<=12; $i++) <option value="{{$i}}" {{ old('birthmonth') == $i ? 'selected' : '' }}>{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option> @endfor
                            </select>
                            <select name="birthyear" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-2 text-xs outline-none">
                                @for($i=2026; $i>=1970; $i--) <option value="{{$i}}" {{ old('birthyear') == $i ? 'selected' : '' }}>{{$i}}</option> @endfor
                            </select>
                        </div>
                        <label class="text-[11px] text-neutral-500 flex items-center mt-2 cursor-pointer">
                            <input type="checkbox" name="hide_age" {{ old('hide_age') ? 'checked' : '' }} class="mr-1.5 rounded border-neutral-300 text-red-700 focus:ring-red-600"> Sembunyikan umur saya
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-neutral-600 mb-2">Jenis Kelamin <span class="text-red-600">*</span></label>
                        <div class="flex items-center gap-5 text-sm text-neutral-700 h-8">
                            <label class="cursor-pointer flex items-center"><input type="radio" name="gender" value="1" {{ old('gender') == '1' ? 'checked' : '' }} class="mr-1.5 text-red-600 focus:ring-red-600"> Laki-laki</label>
                            <label class="cursor-pointer flex items-center"><input type="radio" name="gender" value="2" {{ old('gender', '2') == '2' ? 'checked' : '' }} class="mr-1.5 text-red-600 focus:ring-red-600"> Perempuan</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-600 mb-1.5">Asal Negara <span class="text-red-600">*</span></label>
                    <select name="country" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm outline-none">
                        <option value="Indonesia" {{ old('country', 'Indonesia') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                        <option value="Luar Negeri" {{ old('country') == 'Luar Negeri' ? 'selected' : '' }}>Luar Negeri</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-neutral-600 mb-1.5">Tentang Saya</label>
                    <textarea name="about_me" rows="3" placeholder="Ceritakan sedikit tentang dirimu..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all resize-none">{{ old('about_me') }}</textarea>
                </div>
            </div>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <label class="text-xs text-neutral-600 font-medium flex items-center cursor-pointer">
                <input type="checkbox" required class="mr-2 h-4 w-4 text-red-700 border-neutral-300 rounded focus:ring-red-600">
                Saya menyetujui syarat & ketentuan.
            </label>
            <button type="submit" class="w-full sm:w-auto bg-[#990000] text-white text-sm font-bold px-8 py-3 rounded-lg shadow-sm hover:bg-red-800 transition-colors">
                Daftar Sekarang
            </button>
        </div>
    </form>

    <!-- Navigasi ke Login -->
    <div class="mt-10 pt-6 border-t border-neutral-100 text-center">
        <p class="text-sm text-neutral-500">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-red-700 hover:text-red-800 transition">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection