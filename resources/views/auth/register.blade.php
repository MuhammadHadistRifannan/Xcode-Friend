@extends('layouts.auth')
@section('title', 'Daftar - Xcode Friends')

@section('content')
<div class="w-full max-w-2xl bg-white shadow-lg border border-neutral-200 rounded-2xl overflow-hidden my-8">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-[#7a0000] to-[#c0392b] px-8 py-8 text-white text-center">
        <h1 class="text-2xl font-extrabold tracking-tight mb-1">Bergabung dengan Xcode Friends</h1>
        <p class="text-red-200 text-sm">Lengkapi data kamu untuk mulai terhubung</p>
    </div>

    {{-- Step Indicator --}}
    <div class="flex items-center justify-center bg-neutral-50 border-b border-neutral-200 px-8 py-4">
        @foreach([['1','Paspor'],['2','Info Personal'],['3','Verifikasi']] as $i => [$num, $label])
        <div class="flex items-center">
            @if($i > 0)
            <div id="line-{{ $i }}" class="h-0.5 w-16 md:w-24 bg-neutral-200 transition-all duration-500"></div>
            @endif
            <div class="flex flex-col items-center">
                <div id="step-dot-{{ $num }}" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                    {{ $num == '1' ? 'bg-[#990000] border-[#990000] text-white shadow-md' : 'bg-white border-neutral-300 text-neutral-400' }}">
                    <span id="dot-num-{{ $num }}">{{ $num }}</span>
                    <svg id="dot-check-{{ $num }}" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span id="step-label-{{ $num }}" class="text-[10px] font-bold mt-1.5 transition-all {{ $num == '1' ? 'text-[#990000]' : 'text-neutral-400' }}">{{ $label }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Error Banner (server-side) --}}
    @if($errors->any())
    <div class="mx-8 mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center mb-2">
            <svg class="w-4 h-4 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">Periksa kembali data Anda:</h3>
        </div>
        <ul class="list-disc list-inside text-[11px] text-red-600 ml-4 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="/register" id="register-form" novalidate>
        @csrf

        {{-- ============================================================
             STEP 1: PASPOR
        ============================================================ --}}
        <div id="step-1" class="px-8 py-8 space-y-5">
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-neutral-100">
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-neutral-900">Paspor</h2>
                    <p class="text-[11px] text-neutral-500">Informasi untuk masuk ke akun</p>
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">
                    Alamat Email <span class="text-red-600">*</span>
                </label>
                <input type="email" name="email" id="f_email"
                    value="{{ old('email') }}"
                    placeholder="kamu@example.com"
                    class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all"
                    data-rule="required|email">
                <p class="text-[10px] text-neutral-400 mt-1">Kami tidak akan menampilkan alamat emailmu.</p>
                <p class="field-error text-[10px] text-red-600 mt-1 hidden"></p>
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">
                    Nama Pengguna / Nama Panggilan <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400 font-bold text-sm select-none">@</span>
                    <input type="text" name="username" id="f_username"
                        value="{{ old('username') }}"
                        placeholder="contoh: budi123"
                        class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 pl-8 pr-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all"
                        data-rule="required|min:4|max:18|alnum"
                        maxlength="18">
                </div>
                <p class="text-[10px] text-neutral-400 mt-1">4–18 karakter, hanya huruf dan angka (tanpa spasi)</p>
                <p class="field-error text-[10px] text-red-600 mt-1 hidden"></p>
                {{-- Live checker --}}
                <div id="username-strength" class="mt-1.5 flex gap-1 hidden">
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="us-1"></div>
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="us-2"></div>
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="us-3"></div>
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">
                    Sandi <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="f_password"
                        placeholder="Minimal 6 karakter"
                        class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 pr-10 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all"
                        data-rule="required|min:6">
                    <button type="button" onclick="togglePass('f_password','eye-pass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-700">
                        <svg id="eye-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <p class="field-error text-[10px] text-red-600 mt-1 hidden"></p>
                {{-- Password strength bar --}}
                <div class="mt-2 flex gap-1" id="pass-strength">
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="ps-1"></div>
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="ps-2"></div>
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="ps-3"></div>
                    <div class="h-1 flex-1 rounded-full bg-neutral-200" id="ps-4"></div>
                </div>
                <p class="text-[10px] text-neutral-400 mt-1" id="pass-hint">Kekuatan sandi akan ditampilkan di sini</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-neutral-100">
                <button type="button" onclick="nextStep(1)" id="btn-next-1"
                    class="bg-[#990000] text-white text-sm font-bold px-8 py-2.5 rounded-lg hover:bg-red-800 active:scale-95 transition-all flex items-center gap-2 shadow-sm">
                    Berikutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        {{-- ============================================================
             STEP 2: INFO PERSONAL
        ============================================================ --}}
        <div id="step-2" class="px-8 py-8 space-y-5 hidden">
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-neutral-100">
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-neutral-900">Info Personal</h2>
                    <p class="text-[11px] text-neutral-500">Data diri kamu</p>
                </div>
            </div>

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">Nama Lengkap <span class="text-red-600">*</span></label>
                <input type="text" name="fullname" id="f_fullname"
                    value="{{ old('fullname') }}"
                    class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all"
                    data-rule="required|max:30" maxlength="30">
                <p class="field-error text-[10px] text-red-600 mt-1 hidden"></p>
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">Tanggal Lahir <span class="text-red-600">*</span></label>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="text-[10px] text-neutral-400 mb-1 block">Tahun</label>
                        <select name="birthyear" id="f_birthyear" class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-2 text-sm outline-none focus:border-red-700" data-rule="required">
                            <option value="">Tahun</option>
                            @for($i=date('Y'); $i>=1950; $i--)
                                <option value="{{ $i }}" {{ old('birthyear', 2000) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="text-[10px] text-neutral-400 mb-1 block">Bulan</label>
                        <select name="birthmonth" id="f_birthmonth" class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-2 text-sm outline-none focus:border-red-700" data-rule="required">
                            <option value="">Bulan</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $m)
                                <option value="{{ $k+1 }}" {{ old('birthmonth') == ($k+1) ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="text-[10px] text-neutral-400 mb-1 block">Tanggal</label>
                        <select name="birthday" id="f_birthday" class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-2 text-sm outline-none focus:border-red-700" data-rule="required">
                            <option value="">Tgl</option>
                            @for($i=1; $i<=31; $i++)
                                <option value="{{ $i }}" {{ old('birthday') == $i ? 'selected' : '' }}>{{ str_pad($i,2,'0',STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <label class="flex items-center text-[11px] text-neutral-500 mt-2 cursor-pointer select-none">
                    <input type="checkbox" name="hide_age" {{ old('hide_age') ? 'checked' : '' }} class="mr-2 rounded border-neutral-300 text-red-700 focus:ring-red-600 accent-red-700">
                    Sembunyikan umur saya
                </label>
                <p class="field-error-birth text-[10px] text-red-600 mt-1 hidden"></p>
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-2">Jenis Kelamin <span class="text-red-600">*</span></label>
                <div class="flex gap-3" id="gender-group">
                    @foreach([['1','Cowok','♂'],['2','Cewek','♀'],['0','Sembunyikan','○']] as [$val, $lbl, $icon])
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="gender" value="{{ $val }}" {{ old('gender','1') == $val ? 'checked' : '' }} class="sr-only peer">
                        <div class="border-2 border-neutral-200 rounded-xl p-3 text-center text-xs font-semibold text-neutral-500
                            peer-checked:border-red-700 peer-checked:text-red-700 peer-checked:bg-red-50
                            group-hover:border-neutral-300 transition-all duration-200">
                            <div class="text-xl mb-1">{{ $icon }}</div>
                            {{ $lbl }}
                        </div>
                    </label>
                    @endforeach
                </div>
                <p class="field-error-gender text-[10px] text-red-600 mt-1 hidden"></p>
            </div>

            {{-- Berasal Dari --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">Berasal Dari <span class="text-red-600">*</span></label>
                <select name="country" id="f_country" class="field-input w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm outline-none focus:border-red-700" data-rule="required">
                    <option value="">-- Pilih Provinsi --</option>
                    @php
                    $provinces = ['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara','Luar Negeri'];
                    @endphp
                    @foreach($provinces as $prov)
                        <option value="{{ $prov }}" {{ old('country') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
                <p class="field-error text-[10px] text-red-600 mt-1 hidden"></p>
            </div>

            {{-- Tentangku (Opsional) --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">Tentangku <span class="text-neutral-400 font-normal text-[10px]">— opsional</span></label>
                <textarea name="about_me" rows="3" placeholder="Ceritakan sedikit tentang dirimu..."
                    class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all resize-none">{{ old('about_me') }}</textarea>
            </div>

            {{-- Motto (Opsional) --}}
            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">Motto <span class="text-neutral-400 font-normal text-[10px]">— opsional</span></label>
                <input type="text" name="motto" value="{{ old('motto') }}" placeholder="Motto hidupmu..."
                    class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
            </div>

            <div class="flex justify-between pt-4 border-t border-neutral-100">
                <button type="button" onclick="goToStep(1)" class="border border-neutral-200 text-neutral-600 text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-neutral-50 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Sebelumnya
                </button>
                <button type="button" onclick="nextStep(2)" class="bg-[#990000] text-white text-sm font-bold px-8 py-2.5 rounded-lg hover:bg-red-800 active:scale-95 transition-all flex items-center gap-2 shadow-sm">
                    Berikutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        {{-- ============================================================
             STEP 3: INFO TAMBAHAN + CAPTCHA + TERMS
        ============================================================ --}}
        <div id="step-3" class="px-8 py-8 space-y-5 hidden">
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-neutral-100">
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-neutral-900">Info Tambahan & Verifikasi</h2>
                    <p class="text-[11px] text-neutral-500">Opsional + konfirmasi identitas</p>
                </div>
            </div>

            {{-- Info Tambahan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1.5">Nomor HP / Telp <span class="text-neutral-400 font-normal text-[10px]">— opsional</span></label>
                    <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx"
                        class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1.5">Website / Blog <span class="text-neutral-400 font-normal text-[10px]">— opsional</span></label>
                    <input type="text" name="website" value="{{ old('website') }}" placeholder="https://..."
                        class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">ID Member di Forum / Milis <span class="text-neutral-400 font-normal text-[10px]">— opsional</span></label>
                <input type="text" name="forum_id" value="{{ old('forum_id') }}" placeholder="Username forum/milis kamu"
                    class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-neutral-700 mb-1.5">Film Kesukaan <span class="text-neutral-400 font-normal text-[10px]">— opsional</span></label>
                <textarea name="fav_film" rows="2" placeholder="Film-film favorit kamu..."
                    class="w-full bg-neutral-50 border border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm focus:bg-white focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all resize-none">{{ old('fav_film') }}</textarea>
            </div>

            {{-- CAPTCHA --}}
            <div class="bg-gradient-to-br from-neutral-50 to-neutral-100 border border-neutral-200 rounded-xl p-5">
                <label class="block text-xs font-bold text-neutral-800 mb-3">
                    Verifikasi Keamanan <span class="text-red-600">*</span>
                    <span class="font-normal text-neutral-500 ml-1">— Ketik karakter yang terlihat</span>
                </label>
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- Gambar captcha --}}
                    <div class="relative">
                        <img id="captcha-img"
                            src="{{ route('captcha.generate') }}"
                            alt="CAPTCHA"
                            class="h-12 rounded-lg border-2 border-neutral-200 bg-white shadow-sm cursor-pointer select-none"
                            title="Klik untuk memuat ulang">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition bg-black bg-opacity-20 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                    </div>
                    {{-- Tombol refresh --}}
                    <button type="button" onclick="refreshCaptcha()" title="Muat ulang captcha"
                        class="w-10 h-10 rounded-lg border border-neutral-200 bg-white flex items-center justify-center text-neutral-500 hover:text-red-700 hover:border-red-200 transition shadow-sm">
                        <svg id="refresh-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    {{-- Input --}}
                    <div class="flex-1 min-w-[140px]">
                        <input type="text" name="captcha_answer" id="f_captcha"
                            placeholder="Ketik kode di atas"
                            autocomplete="off"
                            maxlength="8"
                            class="field-input w-full bg-white border-2 border-neutral-200 rounded-lg py-2.5 px-3.5 text-sm font-mono tracking-widest focus:border-red-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all uppercase"
                            data-rule="required" style="letter-spacing: 0.15em">
                    </div>
                </div>
                <p class="field-error-captcha text-[10px] text-red-600 mt-2 hidden"></p>
                @error('captcha_answer')
                    <p class="text-[10px] text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Peraturan & Kondisi --}}
            <div>
                <label class="block text-xs font-bold text-neutral-800 mb-2">Peraturan & Kondisi</label>
                <div class="bg-neutral-50 border border-neutral-200 rounded-lg p-4 h-28 overflow-y-auto text-[11px] text-neutral-600 leading-relaxed space-y-1.5">
                    <p>— Dilarang keras melakukan pelecehan SARA</p>
                    <p>— Dilarang keras mengirim SPAM</p>
                    <p>— Dilarang keras mengisi foto dengan gambar yang mengandung pornografi atau pornoaksi</p>
                    <p>— Setiap tindakan yang dilakukan member di jejaring sosial X-code adalah tanggung jawab pribadi</p>
                    <p>— Member wajib menjaga etika dan sopan santun dalam berkomunikasi</p>
                    <p>— Konten yang bersifat provokatif atau menyerang pihak lain dilarang keras</p>
                </div>
                <label class="flex items-start gap-2.5 mt-3 cursor-pointer select-none" id="terms-label">
                    <input type="checkbox" id="agree_terms" name="agree_terms"
                        class="w-4 h-4 mt-0.5 rounded border-neutral-300 text-red-700 focus:ring-red-600 accent-red-700 flex-shrink-0">
                    <span class="text-xs text-neutral-700 font-medium leading-relaxed">
                        Saya telah membaca, dan setuju dengan peraturan dan kondisi yang berlaku.
                    </span>
                </label>
                <p class="field-error-terms text-[10px] text-red-600 mt-1 hidden"></p>
            </div>

            <div class="flex justify-between pt-4 border-t border-neutral-100">
                <button type="button" onclick="goToStep(2)" class="border border-neutral-200 text-neutral-600 text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-neutral-50 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Sebelumnya
                </button>
                <button type="button" onclick="submitForm()"
                    class="bg-[#990000] text-white text-sm font-bold px-8 py-2.5 rounded-lg hover:bg-red-800 active:scale-95 transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Daftar Sekarang
                </button>
            </div>
        </div>

    </form>

    {{-- Login link --}}
    <div class="px-8 py-5 border-t border-neutral-100 bg-neutral-50 text-center rounded-b-2xl">
        <p class="text-sm text-neutral-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-red-700 hover:text-red-800 transition">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
// =============================================
// KONFIGURASI STEP
// =============================================
const TOTAL_STEPS = 3;
let currentStep = 1;

// Jika ada error server-side, kembali ke step yang relevan
@if($errors->has('email') || $errors->has('username') || $errors->has('password'))
    currentStep = 1;
@elseif($errors->has('fullname') || $errors->has('birthyear') || $errors->has('gender') || $errors->has('country'))
    currentStep = 2;
@elseif($errors->has('captcha_answer'))
    currentStep = 3;
@endif

// =============================================
// ATURAN VALIDASI PER STEP
// =============================================
const STEP_RULES = {
    1: [
        { id: 'f_email',    required: true, type: 'email',  label: 'Email', min: 0, max: 120 },
        { id: 'f_username', required: true, type: 'alnum',  label: 'Username', min: 4, max: 18 },
        { id: 'f_password', required: true, type: 'text',   label: 'Sandi', min: 6 },
    ],
    2: [
        { id: 'f_fullname',   required: true, type: 'text',   label: 'Nama Lengkap', max: 30 },
        { id: 'f_birthyear',  required: true, type: 'select', label: 'Tahun lahir' },
        { id: 'f_birthmonth', required: true, type: 'select', label: 'Bulan lahir' },
        { id: 'f_birthday',   required: true, type: 'select', label: 'Tanggal lahir' },
        { id: 'gender-group', required: true, type: 'radio',  label: 'Jenis Kelamin' },
        { id: 'f_country',    required: true, type: 'select', label: 'Asal daerah' },
    ],
};

// =============================================
// VALIDASI FIELD
// =============================================
function validateField(rule) {
    // Radio khusus (gender)
    if (rule.type === 'radio') {
        const checked = document.querySelector(`input[name="gender"]:checked`);
        if (rule.required && !checked) {
            return { ok: false, msg: 'Jenis kelamin wajib dipilih.' };
        }
        return { ok: true };
    }

    const el = document.getElementById(rule.id);
    if (!el) return { ok: true };
    const val = el.value.trim();

    if (rule.required && val === '') {
        return { ok: false, msg: `${rule.label} wajib diisi.` };
    }
    if (val !== '') {
        if (rule.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            return { ok: false, msg: 'Format email tidak valid.' };
        }
        if (rule.type === 'alnum' && !/^[a-zA-Z0-9]+$/.test(val)) {
            return { ok: false, msg: 'Hanya huruf dan angka, tanpa spasi.' };
        }
        if (rule.min && val.length < rule.min) {
            return { ok: false, msg: `${rule.label} minimal ${rule.min} karakter.` };
        }
        if (rule.max && val.length > rule.max) {
            return { ok: false, msg: `${rule.label} maksimal ${rule.max} karakter.` };
        }
    }
    return { ok: true };
}

// =============================================
// TAMPILKAN / HAPUS ERROR PADA FIELD
// =============================================
function showFieldError(rule, msg) {
    let errorEl;
    if (rule.type === 'radio') {
        errorEl = document.querySelector('.field-error-gender');
    } else {
        const el = document.getElementById(rule.id);
        if (!el) return;
        // Ubah border merah
        el.classList.add('border-red-400', 'bg-red-50');
        el.classList.remove('border-neutral-200', 'bg-neutral-50');
        // Cari elemen error terdekat
        errorEl = el.closest('div') ? el.closest('div').querySelector('.field-error') : null;
    }
    if (errorEl) {
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
    }
}

function clearFieldError(rule) {
    let errorEl;
    if (rule.type === 'radio') {
        errorEl = document.querySelector('.field-error-gender');
    } else {
        const el = document.getElementById(rule.id);
        if (!el) return;
        el.classList.remove('border-red-400', 'bg-red-50');
        el.classList.add('border-neutral-200', 'bg-neutral-50');
        errorEl = el.closest('div') ? el.closest('div').querySelector('.field-error') : null;
    }
    if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.add('hidden');
    }
}

// =============================================
// NEXT STEP (dengan validasi)
// =============================================
function nextStep(step) {
    const rules = STEP_RULES[step] || [];
    let hasError = false;

    rules.forEach(rule => {
        clearFieldError(rule);
        const result = validateField(rule);
        if (!result.ok) {
            showFieldError(rule, result.msg);
            hasError = true;
        }
    });

    if (hasError) {
        // Shake animasi pada tombol
        const btn = document.querySelector(`#step-${step} button[onclick^="nextStep"]`);
        if (btn) {
            btn.classList.add('animate-shake');
            setTimeout(() => btn.classList.remove('animate-shake'), 600);
        }
        return;
    }

    goToStep(step + 1);
}

// =============================================
// PINDAH STEP (tanpa validasi)
// =============================================
function goToStep(step) {
    for (let i = 1; i <= TOTAL_STEPS; i++) {
        document.getElementById('step-' + i).classList.add('hidden');
    }
    document.getElementById('step-' + step).classList.remove('hidden');
    currentStep = step;
    updateStepIndicator(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// =============================================
// UPDATE VISUAL STEP INDICATOR
// =============================================
function updateStepIndicator(step) {
    for (let i = 1; i <= TOTAL_STEPS; i++) {
        const dot    = document.getElementById('step-dot-' + i);
        const numEl  = document.getElementById('dot-num-' + i);
        const check  = document.getElementById('dot-check-' + i);
        const label  = document.getElementById('step-label-' + i);
        const line   = document.getElementById('line-' + (i - 1));

        if (i < step) {
            dot.className   = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-green-600 border-green-600 text-white shadow-md';
            numEl.classList.add('hidden');
            check.classList.remove('hidden');
            label.className = 'text-[10px] font-bold mt-1.5 transition-all text-green-600';
            if (line) line.classList.add('bg-green-400');
        } else if (i === step) {
            dot.className   = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-[#990000] border-[#990000] text-white shadow-md';
            numEl.classList.remove('hidden');
            check.classList.add('hidden');
            label.className = 'text-[10px] font-bold mt-1.5 transition-all text-[#990000]';
            if (line) line.classList.add('bg-red-300');
        } else {
            dot.className   = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-white border-neutral-300 text-neutral-400';
            numEl.classList.remove('hidden');
            check.classList.add('hidden');
            label.className = 'text-[10px] font-bold mt-1.5 transition-all text-neutral-400';
            if (line) { line.classList.remove('bg-green-400', 'bg-red-300'); line.classList.add('bg-neutral-200'); }
        }
    }
}

// =============================================
// SUBMIT FORM (validasi step 3)
// =============================================
function submitForm() {
    let hasError = false;

    // Validasi captcha
    const captcha = document.getElementById('f_captcha');
    const captchaErr = document.querySelector('.field-error-captcha');
    if (!captcha.value.trim()) {
        captchaErr.textContent = 'Kode captcha wajib diisi.';
        captchaErr.classList.remove('hidden');
        captcha.classList.add('border-red-400');
        hasError = true;
    } else {
        captchaErr.classList.add('hidden');
        captcha.classList.remove('border-red-400');
    }

    // Validasi terms
    const terms = document.getElementById('agree_terms');
    const termsErr = document.querySelector('.field-error-terms');
    if (!terms.checked) {
        termsErr.textContent = 'Kamu harus menyetujui peraturan dan kondisi terlebih dahulu.';
        termsErr.classList.remove('hidden');
        hasError = true;
    } else {
        termsErr.classList.add('hidden');
    }

    if (hasError) return;
    document.getElementById('register-form').submit();
}

// =============================================
// REFRESH CAPTCHA
// =============================================
function refreshCaptcha() {
    const img      = document.getElementById('captcha-img');
    const icon     = document.getElementById('refresh-icon');
    const input    = document.getElementById('f_captcha');
    const errEl    = document.querySelector('.field-error-captcha');

    // Animasi ikon putar
    icon.style.transform  = 'rotate(360deg)';
    icon.style.transition = 'transform 0.5s ease';
    setTimeout(() => { icon.style.transform = ''; icon.style.transition = ''; }, 550);

    // Muat ulang gambar captcha BARU (dengan timestamp agar tidak cache)
    img.src = '{{ route("captcha.generate") }}?t=' + Date.now();

    // Kosongkan input dan hapus error
    if (input) { input.value = ''; input.classList.remove('border-red-400'); }
    if (errEl) errEl.classList.add('hidden');
}

// Klik gambar captcha = refresh
document.getElementById('captcha-img').addEventListener('click', refreshCaptcha);

// Jika halaman reload karena error captcha dari server → refresh captcha otomatis
@if($errors->has('captcha_answer'))
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(refreshCaptcha, 300);
    });
@endif


// =============================================
// PASSWORD STRENGTH INDICATOR
// =============================================
document.getElementById('f_password').addEventListener('input', function () {
    const val = this.value;
    const bars = [document.getElementById('ps-1'), document.getElementById('ps-2'), document.getElementById('ps-3'), document.getElementById('ps-4')];
    const hint = document.getElementById('pass-hint');
    let strength = 0;
    if (val.length >= 6) strength++;
    if (val.length >= 10) strength++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;

    const colors = ['bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'];
    const labels = ['Sangat lemah','Lemah','Sedang','Kuat'];
    bars.forEach((b, i) => {
        b.className = 'h-1 flex-1 rounded-full ' + (i < strength ? colors[strength - 1] : 'bg-neutral-200');
    });
    hint.textContent = val ? labels[Math.max(0, strength - 1)] : 'Kekuatan sandi akan ditampilkan di sini';
});

// =============================================
// USERNAME LIVE CHECK
// =============================================
document.getElementById('f_username').addEventListener('input', function () {
    const val = this.value;
    const bars = [document.getElementById('us-1'), document.getElementById('us-2'), document.getElementById('us-3')];
    const str = document.getElementById('username-strength');
    if (val.length > 0) {
        str.classList.remove('hidden');
        let ok = 0;
        if (val.length >= 4) ok++;
        if (/^[a-zA-Z0-9]+$/.test(val)) ok++;
        if (val.length <= 18) ok++;
        const clrs = ok < 2 ? 'bg-red-400' : ok < 3 ? 'bg-yellow-400' : 'bg-green-500';
        bars.forEach((b, i) => {
            b.className = 'h-1 flex-1 rounded-full ' + (i < ok ? clrs : 'bg-neutral-200');
        });
    } else {
        str.classList.add('hidden');
    }
});

// =============================================
// TOGGLE PASSWORD VISIBILITY
// =============================================
function togglePass(inputId) {
    const inp = document.getElementById(inputId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}

// =============================================
// AUTO-UPPERCASE CAPTCHA INPUT
// =============================================
document.getElementById('f_captcha').addEventListener('input', function() {
    const pos = this.selectionStart;
    this.value = this.value.toUpperCase();
    this.setSelectionRange(pos, pos);
});

// =============================================
// INIT
// =============================================
goToStep(currentStep);
</script>

<style>
@keyframes shake {
    0%,100%{ transform: translateX(0); }
    20%    { transform: translateX(-6px); }
    40%    { transform: translateX(6px); }
    60%    { transform: translateX(-4px); }
    80%    { transform: translateX(4px); }
}
.animate-shake { animation: shake 0.5s ease; }
</style>
@endsection