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
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                
                <h3 class="text-lg font-bold text-neutral-900 mb-1">{{ $user->fullname }}</h3>
                <p class="text-xs text-neutral-500 mb-6">{{ $user->roles ?? 'Junior Penetration Tester' }}</p>
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
                <a href="?tab=blokir" class="text-[11px] font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'blokir' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Daftar Blokir</a>
            </div>

            <!-- ISI TAB -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 mb-6">
                
                
                

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

                        @if(isset($customFields) && count($customFields) > 0)
                            <div class="border-t border-neutral-100 pt-6 mt-6 space-y-6">
                                <h4 class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-4">Informasi Tambahan</h4>
                                @foreach($customFields as $index => $field)
                                    @if($field->type !== 'Disabled')
                                        @php 
                                            $colName = 'var' . ($index + 1); 
                                            $value = old($colName, $user->{$colName});
                                        @endphp
                                        <div class="flex items-center gap-6">
                                            <label class="w-1/3 text-xs font-semibold text-neutral-600">
                                                {{ $field->name }}
                                                @if($field->required) <span class="text-red-500">*</span> @endif
                                            </label>
                                            
                                            <div class="w-2/3">
                                                @if($field->type === 'Text Box')
                                                    <input type="text" name="{{ $colName }}" value="{{ $value }}" {{ $field->required ? 'required' : '' }} class="w-full bg-white border border-neutral-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-red-700 transition">
                                                @elseif($field->type === 'Select Box')
                                                    @php $options = explode("\n", str_replace("\r", "", $field->options)); @endphp
                                                    <select name="{{ $colName }}" {{ $field->required ? 'required' : '' }} class="w-full bg-white border border-neutral-300 rounded px-4 py-2 text-sm focus:outline-none focus:border-red-700 transition appearance-none">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach($options as $opt)
                                                            @if(trim($opt) !== '')
                                                                <option value="{{ trim($opt) }}" {{ $value == trim($opt) ? 'selected' : '' }}>{{ trim($opt) }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                                @if($field->description)
                                                    <p class="text-[10px] text-neutral-400 mt-1">{{ $field->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

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
                                            <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover">
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
                                                <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
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

                @elseif($tab === 'blokir')
                    <!-- TAB 6: DAFTAR BLOKIR -->
                    <h2 class="text-lg font-bold text-neutral-900 mb-2">Daftar Pengguna Diblokir</h2>
                    <p class="text-xs text-neutral-500 mb-6 border-b border-neutral-100 pb-4">Kelola pengguna yang telah kamu blokir.</p>
                    
                    <div class="mb-4 relative">
                        <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                        <input type="text" id="searchBlocked" placeholder="Cari nama pengguna..." class="w-full pl-9 pr-4 py-2.5 text-sm bg-neutral-50 border border-neutral-200 rounded-lg focus:outline-none focus:border-red-700 transition">
                    </div>

                    <div class="space-y-4" id="blockedList">
                        @forelse($blockedUsers as $blocked)
                            <div class="blocked-item flex items-center justify-between p-4 border border-neutral-100 bg-neutral-50 rounded-lg hover:bg-neutral-100 transition" data-name="{{ strtolower($blocked->fullname) }}" data-username="{{ strtolower($blocked->username) }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold text-sm shrink-0">
                                        @if(!empty($blocked->avatar))
                                            <img src="{{ asset('uploads/avatars/' . $blocked->avatar) }}" alt="{{ $blocked->fullname }}" class="w-full h-full rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <span style="display:none;">{{ strtoupper(substr($blocked->fullname, 0, 1)) }}</span>
                                        @else
                                            <span>{{ strtoupper(substr($blocked->fullname, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-neutral-800">{{ $blocked->fullname }}</h4>
                                        <p class="text-[11px] text-neutral-500">{{ '@' . $blocked->username }}</p>
                                    </div>
                                </div>
                                <form id="unblockForm-{{ $blocked->id }}" action="{{ route('friends.unblock', $blocked->id) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="openUnblockModal({{ $blocked->id }}, '{{ e($blocked->fullname) }}')" class="text-xs font-semibold text-neutral-500 hover:text-red-700 bg-white border border-neutral-200 hover:border-red-200 px-3 py-1.5 rounded-md transition shadow-sm">
                                        Buka Blokir
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="py-12 text-center text-neutral-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                <p class="text-sm">Belum ada pengguna yang diblokir.</p>
                            </div>
                        @endforelse
                        
                        <div id="noResult" class="hidden py-8 text-center text-neutral-400">
                            <p class="text-sm">Tidak ditemukan hasil pencarian.</p>
                        </div>
                    </div>

                    {{-- Modal Konfirmasi Buka Blokir --}}
                    <div id="unblockModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="unblockModalContent">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-neutral-900">Buka Blokir Pengguna</h3>
                                        <p class="text-xs text-neutral-500">Tindakan ini akan mengizinkan interaksi.</p>
                                    </div>
                                </div>
                                <p class="text-sm text-neutral-600 mb-6 leading-relaxed">
                                    Yakin ingin membuka blokir <span id="modalUserName" class="font-bold text-neutral-900"></span>? Pengguna ini akan bisa berinteraksi denganmu kembali.
                                </p>
                                <div class="flex gap-3">
                                    <button type="button" onclick="closeUnblockModal()" class="flex-1 px-4 py-2.5 text-sm font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-xl transition">Batal</button>
                                    <button type="button" onclick="submitUnblockForm()" class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-red-700 hover:bg-red-800 rounded-xl transition shadow-md hover:shadow-lg transform active:scale-95">Ya, Buka Blokir</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        let currentUnblockId = null;

                        function openUnblockModal(userId, userName) {
                            currentUnblockId = userId;
                            document.getElementById('modalUserName').textContent = userName;
                            const modal = document.getElementById('unblockModal');
                            const modalContent = document.getElementById('unblockModalContent');
                            
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                            
                            // Animasi muncul
                            setTimeout(() => {
                                modal.classList.remove('opacity-0');
                                modal.classList.add('opacity-100');
                                modalContent.classList.remove('scale-95');
                                modalContent.classList.add('scale-100');
                            }, 10);
                        }

                        function closeUnblockModal() {
                            const modal = document.getElementById('unblockModal');
                            const modalContent = document.getElementById('unblockModalContent');
                            
                            // Animasi hilang
                            modal.classList.remove('opacity-100');
                            modal.classList.add('opacity-0');
                            modalContent.classList.remove('scale-100');
                            modalContent.classList.add('scale-95');
                            
                            setTimeout(() => {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                                currentUnblockId = null;
                            }, 300);
                        }

                        function submitUnblockForm() {
                            if (currentUnblockId) {
                                document.getElementById('unblockForm-' + currentUnblockId).submit();
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            const searchInput = document.getElementById('searchBlocked');
                            const items = document.querySelectorAll('.blocked-item');
                            const noResult = document.getElementById('noResult');

                            if (searchInput) {
                                searchInput.addEventListener('input', function() {
                                    const q = this.value.toLowerCase().trim();
                                    let visible = 0;
                                    items.forEach(item => {
                                        const name = item.dataset.name || '';
                                        const username = item.dataset.username || '';
                                        if (name.includes(q) || username.includes(q)) {
                                            item.style.display = '';
                                            visible++;
                                        } else {
                                            item.style.display = 'none';
                                        }
                                    });
                                    if(noResult) {
                                        if(visible === 0 && q !== '') {
                                            noResult.classList.remove('hidden');
                                        } else {
                                            noResult.classList.add('hidden');
                                        }
                                    }
                                });
                            }
                        });
                    </script>

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
            <x-sidebar-right />
        </div>

    </div>
</div>
@endsection
