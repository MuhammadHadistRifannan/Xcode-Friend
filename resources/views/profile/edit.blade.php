@extends('layouts.app')
@section('title', 'Akunku - ' . $user->fullname)

@section('content')
<div class="max-w-[95%] xl:max-w-7xl mx-auto w-full pb-10 mt-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <h1 class="text-2xl font-bold text-neutral-900 tracking-tight">Akunku</h1>
        </div>
        <a href="{{ route('profile.show', $user->username) }}" class="flex items-center text-sm font-bold text-neutral-600 bg-white border border-neutral-200 px-4 py-2 rounded-lg hover:bg-neutral-50 hover:text-red-700 transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Profil
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI (Profil Info Singkat) -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-neutral-100 border-4 border-white shadow-md mb-4 overflow-hidden relative group">
                    <img src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->fullname).'&background=E5E5E5&size=128' }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                
                <h3 class="text-lg font-bold text-neutral-900 mb-1">{{ $user->fullname }}</h3>
                <p class="text-xs text-neutral-500 mb-6">{{ $user->roles ?? 'Junior Penetration Tester' }}</p>
                
                <div class="grid grid-cols-3 gap-2 border-t border-neutral-100 pt-5">
                    <div><p class="text-lg font-extrabold text-neutral-800">12</p><p class="text-[10px] font-bold text-neutral-500 uppercase">Projects</p></div>
                    <div><p class="text-lg font-extrabold text-neutral-800">8</p><p class="text-[10px] font-bold text-neutral-500 uppercase">Certs</p></div>
                    <div><p class="text-lg font-extrabold text-neutral-800">34</p><p class="text-[10px] font-bold text-neutral-500 uppercase">Points</p></div>
                </div>
            </div>

            <!-- Media X-CODE -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h4 class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest mb-4">MEDIA X-CODE</h4>
                <div class="space-y-3">
                    <a href="#" class="flex items-center text-xs font-semibold text-neutral-700 hover:text-red-700 transition"><svg class="w-4 h-4 mr-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg> Website X-code</a>
                    <a href="#" class="flex items-center text-xs font-semibold text-neutral-700 hover:text-red-700 transition"><svg class="w-4 h-4 mr-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg> Forum X-code</a>
                    <a href="#" class="flex items-center text-xs font-semibold text-neutral-700 hover:text-red-700 transition"><svg class="w-4 h-4 mr-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg> Blog X-code</a>
                    <a href="#" class="flex items-center text-xs font-semibold text-neutral-700 hover:text-red-700 transition"><svg class="w-4 h-4 mr-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> Bootcamp Pentest</a>
                    <a href="#" class="flex items-center text-xs font-semibold text-neutral-700 hover:text-red-700 transition"><svg class="w-4 h-4 mr-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> X-code Webinar</a>
                </div>
            </div>
        </div>

        <!-- KOLOM TENGAH (Tab Pengaturan Utama) -->
        <div class="lg:col-span-6">
            
            @php $tab = request()->query('tab', 'informasi'); @endphp
            
            <!-- TABS -->
            <div class="flex items-center space-x-8 border-b border-neutral-200 mb-6 overflow-x-auto whitespace-nowrap">
                <a href="?tab=informasi" class="text-[11px] font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'informasi' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Informasiku</a>
                <a href="?tab=gambar" class="text-[11px] font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'gambar' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Gambar Pengenal</a>
                <a href="?tab=pemberitahuan" class="text-[11px] font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'pemberitahuan' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Pemberitahuan</a>
                <a href="?tab=privasi" class="text-[11px] font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'privasi' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Privasi</a>
                <a href="?tab=sandi" class="text-[11px] font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'sandi' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Sandi</a>
            </div>

            <!-- ISI TAB -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 mb-6">
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-medium">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($tab === 'informasi')
                    <!-- TAB 1: INFORMASIKU -->
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" value="informasi">
                        
                        <div class="flex items-center gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600">Nama Lengkap</label>
                            <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" required class="w-2/3 bg-white border border-neutral-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-red-700 transition">
                        </div>

                        <div class="flex items-start gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600 mt-2">Username</label>
                            <div class="w-2/3">
                                <input type="text" value="{{ $user->username }}" disabled class="w-full bg-neutral-100 border border-neutral-200 text-neutral-500 rounded px-4 py-2 text-sm cursor-not-allowed">
                                <p class="text-[10px] text-neutral-400 mt-1">Username tidak dapat diubah setelah registrasi.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-2/3 bg-white border border-neutral-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-red-700 transition">
                        </div>

                        <div class="flex items-center gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600">Tanggal Lahir</label>
                            <div class="w-2/3 grid grid-cols-3 gap-2">
                                <select name="birthday" class="bg-white border border-neutral-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-red-700">
                                    <option value="">Hari</option>
                                    @for($i=1; $i<=31; $i++)
                                        <option value="{{ $i }}" {{ old('birthday', $user->birthday) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select name="birthmonth" class="bg-white border border-neutral-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-red-700">
                                    <option value="">Bulan</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $m)
                                        <option value="{{ $k+1 }}" {{ old('birthmonth', $user->birthmonth) == ($k+1) ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                <select name="birthyear" class="bg-white border border-neutral-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-red-700">
                                    <option value="">Tahun</option>
                                    @for($i=date('Y'); $i>=1950; $i--)
                                        <option value="{{ $i }}" {{ old('birthyear', $user->birthyear) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600">Jenis Kelamin</label>
                            <div class="w-2/3 flex items-center space-x-6">
                                <label class="flex items-center text-sm text-neutral-700 cursor-pointer">
                                    <input type="radio" name="gender" value="1" {{ old('gender', $user->gender) == 1 ? 'checked' : '' }} class="mr-2 accent-red-700">
                                    Laki-laki
                                </label>
                                <label class="flex items-center text-sm text-neutral-700 cursor-pointer">
                                    <input type="radio" name="gender" value="2" {{ old('gender', $user->gender) == 2 ? 'checked' : '' }} class="mr-2 accent-red-700">
                                    Perempuan
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600">Lokasi</label>
                            <input type="text" name="location" value="{{ old('location', $user->location) }}" class="w-2/3 bg-white border border-neutral-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-red-700 transition">
                        </div>

                        <div class="flex items-start gap-6">
                            <label class="w-1/3 text-xs font-semibold text-neutral-600 mt-2">Tentang Saya</label>
                            <textarea name="about_me" rows="4" placeholder="Tuliskan sedikit tentang diri Anda..." class="w-2/3 bg-white border border-neutral-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-red-700 transition resize-none">{{ old('about_me', $user->about_me) }}</textarea>
                        </div>

                        <div class="border-t border-neutral-100 pt-6 flex justify-end items-center mt-8">
                            <a href="{{ route('profile.show', $user->username) }}" class="text-xs font-bold text-neutral-500 mr-6 hover:text-neutral-800 transition">Batal</a>
                            <button type="submit" class="bg-[#990000] text-white text-xs font-bold tracking-wide px-8 py-2.5 rounded hover:bg-red-800 transition shadow">Simpan Perubahan</button>
                        </div>
                    </form>

                @elseif($tab === 'gambar')
                    <!-- TAB 2: GAMBAR PENGENAL & BANNER SAMPUL -->
                    <div class="space-y-12">
                        <!-- BAGIAN: GAMBAR PENGENAL (AVATAR) -->
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="type" value="avatar">
                            
                            <div>
                                <h4 class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest border-b border-neutral-100 pb-2 mb-4">AVATAR (GAMBAR PENGENAL)</h4>
                                
                                <div class="flex items-center gap-8 mb-6">
                                    <div class="w-32 h-32 rounded-full border border-neutral-300 p-1 flex-shrink-0">
                                        <div class="w-full h-full rounded-full overflow-hidden bg-neutral-100">
                                            <img src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->fullname).'&background=E5E5E5&size=128' }}" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="border-2 border-dashed border-neutral-300 rounded-lg p-6 flex flex-col items-center justify-center bg-neutral-50 hover:bg-neutral-100 transition cursor-pointer" onclick="document.getElementById('avatar_upload').click()">
                                            <svg class="w-8 h-8 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <p class="text-sm font-semibold text-neutral-700">Ganti Avatar</p>
                                            <p class="text-[10px] text-neutral-500 mt-1">JPG, PNG, GIF Max 5MB</p>
                                            <input type="file" name="avatar" id="avatar_upload" class="hidden" accept="image/*" onchange="this.form.submit()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- BAGIAN: BANNER SAMPUL (COVER) -->
                        <form action="{{ route('profile.background.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <div>
                                <h4 class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest border-b border-neutral-100 pb-2 mb-4">BANNER SAMPUL</h4>
                                
                                <div class="mb-6">
                                    <div class="w-full h-40 rounded-xl border border-neutral-300 p-1 mb-4">
                                        <div class="w-full h-full rounded-lg overflow-hidden bg-neutral-100 relative">
                                            @if($user->profile && $user->profile->background)
                                                <img src="{{ asset('storage/backgrounds/' . $user->profile->background) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-r from-neutral-200 to-neutral-300 flex items-center justify-center">
                                                    <span class="text-neutral-500 text-sm font-bold opacity-50">Belum ada banner</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="border-2 border-dashed border-neutral-300 rounded-lg p-6 flex flex-col items-center justify-center bg-neutral-50 hover:bg-neutral-100 transition cursor-pointer" onclick="document.getElementById('background_upload').click()">
                                        <svg class="w-8 h-8 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-sm font-semibold text-neutral-700">Unggah Banner Baru</p>
                                        <p class="text-[10px] text-neutral-500 mt-1">Disarankan: 1200x400px (JPG, PNG, WebP Max 2MB)</p>
                                        <input type="file" name="background" id="background_upload" class="hidden" accept="image/*" onchange="this.form.submit()">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                @elseif($tab === 'pemberitahuan')
                    <!-- TAB 3: PEMBERITAHUAN -->
                    @php $settings = json_decode($user->settings, true) ?? []; @endphp
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="pemberitahuan">
                        
                        <h2 class="text-lg font-bold text-neutral-900 mb-2">Pengaturan Pemberitahuan</h2>
                        <p class="text-xs text-neutral-500 mb-8 border-b border-neutral-100 pb-4">Konfigurasi preferensi notifikasi sistem untuk akun Anda.</p>

                        <div class="space-y-6">
                            @php
                                $notifs = [
                                    'pesan' => ['Pesan privat', 'Notifikasi saat menerima pesan pribadi.'],
                                    'req_teman' => ['Permintaan pertemanan', 'Peringatan untuk koneksi baru.'],
                                    'acc_teman' => ['Permintaan pertemanan diterima', 'Status pembaruan koneksi.'],
                                    'dinding' => ['Tulisan Dinding', 'Aktivitas pada profil Anda.'],
                                    'komentar' => ['Komentar Sesomaniac', 'Interaksi dari anggota komunitas.'],
                                    'grup' => ['Balasan Grup', 'Pembaruan diskusi tim.']
                                ];
                            @endphp

                            @foreach($notifs as $key => $n)
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-800">{{ $n[0] }}</h4>
                                    <p class="text-[11px] text-neutral-500">{{ $n[1] }}</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notif_{{ $key }}" value="1" class="sr-only peer" {{ ($settings['notif_'.$key] ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-700"></div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-neutral-100 pt-6 flex justify-end mt-8">
                            <button type="submit" class="bg-[#990000] text-white text-xs font-bold tracking-wide px-8 py-2.5 rounded hover:bg-red-800 transition shadow">Simpan</button>
                        </div>
                    </form>

                @elseif($tab === 'privasi')
                    <!-- TAB 4: PRIVASI -->
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="privasi">
                        
                        <h2 class="text-lg font-bold text-neutral-900 mb-2">Pengaturan Privasi</h2>
                        <p class="text-xs text-neutral-500 mb-8 border-b border-neutral-100 pb-4">Kelola visibilitas profil dan interaksi komunitas Anda.</p>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-neutral-800 mb-2">Privasi profil</label>
                                <select name="profile_permission" class="w-full bg-white border border-neutral-300 text-sm text-neutral-700 rounded px-4 py-2.5 focus:outline-none focus:border-red-700 appearance-none">
                                    <option value="0" {{ $user->profile_permission == 0 ? 'selected' : '' }}>Siapapun dapat melihat profilku, dan berkomentar di dindingku</option>
                                    <option value="1" {{ $user->profile_permission == 1 ? 'selected' : '' }}>Hanya Teman</option>
                                    <option value="2" {{ $user->profile_permission == 2 ? 'selected' : '' }}>Hanya Saya</option>
                                </select>
                            </div>

                            <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200 flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-800">Community browse</h4>
                                    <p class="text-[11px] text-neutral-500">Sembunyikan saya dari telusur anggota</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="hide_search" value="1" class="sr-only peer" {{ ($user->hide_me ?? 0) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-700"></div>
                                </label>
                            </div>

                            <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200">
                                <h4 class="text-sm font-bold text-neutral-800 mb-3">Semua orang dapat melihat hal yang kita sukai</h4>
                                <div class="flex items-center space-x-6">
                                    <label class="flex items-center text-sm text-neutral-700 cursor-pointer font-medium">
                                        <input type="radio" name="hide_likes" value="0" {{ !($user->hide_me ?? 0) ? 'checked' : '' }} class="mr-2 accent-red-700">
                                        Ya
                                    </label>
                                    <label class="flex items-center text-sm text-neutral-700 cursor-pointer font-medium">
                                        <input type="radio" name="hide_likes" value="1" {{ ($user->hide_me ?? 0) ? 'checked' : '' }} class="mr-2 accent-red-700">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-neutral-100 pt-6 flex justify-end mt-8">
                            <button type="submit" class="bg-[#990000] text-white text-xs font-bold tracking-wide px-8 py-2.5 rounded hover:bg-red-800 transition shadow">Simpan</button>
                        </div>
                    </form>


                @elseif($tab === 'sandi')
                    <!-- TAB 5: KATA SANDI -->
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="sandi">
                        
                        <h2 class="text-lg font-bold text-neutral-900 mb-8 border-b border-neutral-100 pb-4">Ubah Kata Sandi</h2>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-neutral-800 mb-2">Sandi Sekarang</label>
                                <div class="relative">
                                    <input type="password" name="current_password" required placeholder="Masukkan sandi saat ini" class="w-full bg-neutral-50 border border-neutral-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-red-700 transition">
                                    <button type="button" class="absolute right-3 top-2.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-800 mb-2">Sandi baru</label>
                                <div class="relative">
                                    <input type="password" name="password" required placeholder="Masukkan sandi baru" class="w-full bg-neutral-50 border border-neutral-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-red-700 transition">
                                    <button type="button" class="absolute right-3 top-2.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-800 mb-2">Ketik ulang sandi baru</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" required placeholder="Konfirmasi sandi baru" class="w-full bg-neutral-50 border border-neutral-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-red-700 transition">
                                    <button type="button" class="absolute right-3 top-2.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-neutral-100 pt-6 flex justify-end mt-8">
                            <button type="submit" class="bg-[#990000] text-white text-xs font-bold tracking-wide px-8 py-2.5 rounded hover:bg-red-800 transition shadow">Simpan</button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Pesan Edit Block dari Design 1 -->
            @if($tab === 'informasi')
                <div class="bg-white border border-neutral-200 rounded-lg p-4 flex items-center text-xs text-neutral-600 shadow-sm">
                    <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Go to "Admin CP" - "Themes" - "Manage Blocks" to edit this message.
                </div>
            @endif

        </div>

        <!-- KOLOM KANAN (Widgets) -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Widget Review -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 text-center">
                <h4 class="text-xs font-bold text-neutral-600 mb-2">Google Reviews</h4>
                <div class="flex justify-center text-yellow-400 mb-1 space-x-1">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4 fill-current text-neutral-200" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-2xl font-black text-neutral-900 mb-1">4.9</p>
                <a href="#" class="text-[11px] font-medium text-blue-600 hover:underline">532 Reviews</a>
            </div>

            <!-- Network Links -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h4 class="text-sm font-bold text-neutral-800 mb-4">Network Links</h4>
                <div class="space-y-3">
                    <a href="#" class="flex justify-between items-center bg-blue-50/50 hover:bg-blue-50 border border-blue-100 rounded-lg p-3 transition group">
                        <div class="flex items-center text-blue-700">
                            <div class="bg-white p-1.5 rounded-md border border-blue-100 shadow-sm mr-3"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></div>
                            <span class="text-xs font-semibold">LinkedIn</span>
                        </div>
                        <svg class="w-4 h-4 text-blue-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#" class="flex justify-between items-center bg-indigo-50/50 hover:bg-indigo-50 border border-indigo-100 rounded-lg p-3 transition group">
                        <div class="flex items-center text-indigo-700">
                            <div class="bg-white p-1.5 rounded-md border border-indigo-100 shadow-sm mr-3"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.99 18.064c-.381.16-.708-.282-.544-.645l.985-2.176c-1.397-1.89-2.073-4.148-1.865-6.52.28-3.195 2.502-5.918 5.6-6.732 3.86-1.014 7.647.464 9.538 3.513 1.944 3.13 1.408 7.33-1.344 9.873-1.92 1.774-4.576 2.65-7.143 2.518-1.385-.072-2.707-.482-3.844-1.127l-1.383 1.296zm4.997-6.264c-1.077 0-1.954.89-1.954 1.986s.877 1.987 1.954 1.987 1.954-.89 1.954-1.987-.877-1.986-1.954-1.986zm5.992 0c-1.076 0-1.953.89-1.953 1.986s.877 1.987 1.953 1.987 1.954-.89 1.954-1.987-.878-1.986-1.954-1.986zm-5.992-5.462c-1.077 0-1.954.89-1.954 1.986s.877 1.987 1.954 1.987 1.954-.89 1.954-1.987-.877-1.986-1.954-1.986zm5.992 0c-1.076 0-1.953.89-1.953 1.986s.877 1.987 1.953 1.987 1.954-.89 1.954-1.987-.878-1.986-1.954-1.986z"/></svg></div>
                            <span class="text-xs font-semibold">phpBB Group</span>
                        </div>
                        <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#" class="flex justify-between items-center bg-sky-50/50 hover:bg-sky-50 border border-sky-100 rounded-lg p-3 transition group">
                        <div class="flex items-center text-sky-700">
                            <div class="bg-white p-1.5 rounded-md border border-sky-100 shadow-sm mr-3"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24h11.495v-9.294H9.691v-3.622h3.129V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.324V1.325C24 .597 23.403 0 22.675 0z"/></svg></div>
                            <span class="text-xs font-semibold">Facebook</span>
                        </div>
                        <svg class="w-4 h-4 text-sky-400 group-hover:text-sky-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
