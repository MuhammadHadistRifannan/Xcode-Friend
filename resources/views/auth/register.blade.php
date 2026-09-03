@extends('layouts.auth')
@section('title', 'Daftar - Xcode Friends')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 w-full max-w-2xl p-6 sm:p-8 mx-auto my-8 max-h-[85vh] overflow-y-auto custom-scrollbar relative">
    
    <div class="text-center mb-6">
        <!-- Logo -->
        <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-14 w-auto mx-auto mb-4 object-contain hidden lg:block">
        
        <h2 class="text-2xl font-extrabold text-neutral-900 tracking-tight">Create Account</h2>
        <p class="text-sm text-neutral-500 mt-1">Create your account to get started.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-600 text-xs p-3 rounded-lg mb-6">
            <div class="flex items-center font-bold mb-1">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Periksa kembali data Anda:
            </div>
            <ul class="list-disc list-inside ml-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register" id="register-form" class="space-y-6" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
        @csrf

        <!-- SECTION: Basic Info -->
        <div>
            <h3 class="text-xs font-bold tracking-widest text-neutral-400 uppercase border-b border-neutral-100 pb-2 mb-4">Informasi Dasar</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Full Name --}}
                <div>
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Full Name <span class="text-[#990000]">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input type="text" name="fullname" value="{{ old('fullname') }}" required placeholder="Nama lengkap" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-4 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                    </div>
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Username <span class="text-[#990000]">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 font-bold select-none">@</span>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Username" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-9 pr-4 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                    </div>
                </div>

                {{-- Email --}}
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Email <span class="text-[#990000]">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="kamu@example.com" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-4 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Password <span class="text-[#990000]">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input type="password" id="reg-password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-10 pr-10 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition-all">
                        <button type="button" onclick="togglePassword('reg-password', 'eye-reg-pass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400 hover:text-neutral-600 transition focus:outline-none">
                            <svg id="eye-reg-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password (Tidak ada di controller saat ini tapi di design biasanya ada, kita sesuaikan saja jika dibutuhkan. AuthController current tidak cek password_confirmation tapi ini visual sesuai design) --}}
                {{-- Controller: password => ['required', 'string', 'min:6'] (no confirmed) --}}
            </div>
        </div>

        <!-- SECTION: Personal Data -->
        <div>
            <h3 class="text-xs font-bold tracking-widest text-neutral-400 uppercase border-b border-neutral-100 pb-2 mb-4 mt-2">Data Diri</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tanggal Lahir --}}
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Tanggal Lahir <span class="text-[#990000]">*</span></label>
                    <div class="flex gap-2">
                        <select name="birthday" class="w-1/3 bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-2 text-sm outline-none focus:border-[#990000]" required>
                            <option value="">Tgl</option>
                            @for($i=1; $i<=31; $i++)
                                <option value="{{ $i }}" {{ old('birthday') == $i ? 'selected' : '' }}>{{ str_pad($i,2,'0',STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                        <select name="birthmonth" class="w-1/3 bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-2 text-sm outline-none focus:border-[#990000]" required>
                            <option value="">Bulan</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $m)
                                <option value="{{ $k+1 }}" {{ old('birthmonth') == ($k+1) ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                        <select name="birthyear" class="w-1/3 bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-2 text-sm outline-none focus:border-[#990000]" required>
                            <option value="">Tahun</option>
                            @for($i=date('Y'); $i>=1950; $i--)
                                <option value="{{ $i }}" {{ old('birthyear', 2000) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <label class="flex items-center mt-2 cursor-pointer w-fit">
                        <input type="checkbox" name="hide_age" {{ old('hide_age') ? 'checked' : '' }} class="w-3.5 h-3.5 text-[#990000] border-neutral-300 rounded focus:ring-[#990000]">
                        <span class="ml-2 text-[10px] text-neutral-500">Sembunyikan umur saya</span>
                    </label>
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Jenis Kelamin <span class="text-[#990000]">*</span></label>
                    <select name="gender" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#990000]" required>
                        <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>Perempuan</option>
                        <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Sembunyikan</option>
                    </select>
                </div>

                {{-- Asal Daerah --}}
                <div>
                    <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-1.5">Provinsi / Asal <span class="text-[#990000]">*</span></label>
                    <select name="country" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#990000]" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara','Luar Negeri'] as $prov)
                            <option value="{{ $prov }}" {{ old('country') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION: Optional Info -->
        <div>
            <h3 class="text-xs font-bold tracking-widest text-neutral-400 uppercase border-b border-neutral-100 pb-2 mb-4 mt-2">Info Opsional</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="No. HP" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#990000]">
                <input type="text" name="forum_id" value="{{ old('forum_id') }}" placeholder="Forum ID" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#990000]">
                <input type="text" name="motto" value="{{ old('motto') }}" placeholder="Motto" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#990000] sm:col-span-2">
            </div>
        </div>

        <!-- SECTION: Captcha & Terms -->
        <div class="bg-neutral-50/50 p-4 rounded-xl border border-neutral-100">
            <label class="block text-[11px] font-bold tracking-wider text-neutral-500 uppercase mb-2">Keamanan <span class="text-[#990000]">*</span></label>
            <div class="flex items-center gap-3 mb-4">
                <img id="captcha-img" src="{{ route('captcha.generate') }}" alt="CAPTCHA" class="h-10 rounded-lg border border-neutral-200 cursor-pointer" onclick="this.src='{{ route('captcha.generate') }}?t='+Date.now()" title="Klik untuk refresh">
                <input type="text" name="captcha_answer" placeholder="Ketik kode" required autocomplete="off" class="flex-1 min-w-0 bg-white border border-neutral-300 rounded-lg py-2 px-3 text-sm font-mono tracking-widest uppercase focus:border-[#990000] outline-none">
            </div>

            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                <input type="checkbox" name="agree_terms" required class="w-4 h-4 mt-0.5 text-[#990000] border-neutral-300 rounded focus:ring-[#990000] flex-shrink-0">
                <span class="text-[11px] text-neutral-600 leading-relaxed">
                    Saya menyetujui <a href="#" class="text-[#990000] font-bold">Syarat & Ketentuan</a> komunitas, termasuk larangan SARA, SPAM, dan pornografi.
                </span>
            </label>
        </div>

        <button type="submit" class="w-full bg-[#990000] text-white font-bold tracking-wide rounded-lg py-3 text-sm hover:bg-red-800 transition-colors shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
            Create Account
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-neutral-100 text-center">
        <p class="text-sm text-neutral-500">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-[#990000] hover:text-red-800 transition">Login</a>
        </p>
    </div>
</div>

<style>
/* Custom Scrollbar for compact form */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e5e5; border-radius: 20px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #cccccc; }
</style>
@endsection