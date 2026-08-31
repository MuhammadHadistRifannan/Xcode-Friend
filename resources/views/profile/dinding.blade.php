@extends('layouts.app')
@section('title', $profileUser->fullname . ' - Profile')

@section('content')
<div class="max-w-[95%] xl:max-w-7xl mx-auto w-full pb-10">
    <!-- Area Cover / Background Profil -->
    <div class="relative w-full h-48 md:h-64 bg-neutral-200 rounded-xl overflow-hidden shadow-sm mb-6 group">
        @if($profileUser->profile && $profileUser->profile->background)
            <!-- Menampilkan Cover Jika Ada -->
            <img src="{{ asset('storage/backgrounds/' . $profileUser->profile->background) }}" class="w-full h-full object-cover" alt="Cover Profile">
        @else
            <!-- Gambar Default Cover Jika Belum Punya -->
            <div class="w-full h-full bg-gradient-to-r from-neutral-300 to-neutral-200"></div>
        @endif

        <!-- Tombol Upload (Hanya Muncul Jika Profil Milik User yang Login) -->
        @if(auth()->check() && auth()->user()->id === $profileUser->id)
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition duration-300">
                <form action="{{ route('profile.background.update') }}" method="POST" enctype="multipart/form-data" id="form-cover-upload">
                    @csrf
                    <!-- Input file disembunyikan -->
                    <input type="file" name="background" id="background-input" class="hidden" accept="image/*" onchange="document.getElementById('form-cover-upload').submit();">
                    
                    <!-- Tombol visual yang dapat di-klik -->
                    <button type="button" onclick="document.getElementById('background-input').click();" class="bg-black bg-opacity-50 hover:bg-opacity-70 text-white text-xs font-bold px-4 py-2 rounded-md backdrop-blur-sm transition shadow-md flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Ganti Sampul
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Bagian Atas: Nama & Tabs -->
    <div class="mb-6">
        <h1 class="text-sm font-extrabold text-neutral-900 uppercase tracking-widest mb-4">{{ $profileUser->fullname }}</h1>
        
        <div class="flex items-center space-x-8 border-b border-neutral-200">
            <a href="?tab=dinding" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'dinding' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Dinding</a>
            <a href="?tab=menyukai" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'menyukai' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Menyukai</a>
            <a href="?tab=foto" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'foto' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Foto</a>
            <a href="?tab=video" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'video' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Video</a>
        </div>
    </div>

    <!-- Grid 3 Kolom Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI (Sidebar Profil) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Kartu Identitas -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5 text-center relative">
                <div class="w-24 h-24 mx-auto rounded-full bg-neutral-100 border border-neutral-200 mb-3 overflow-hidden">
                    <img src="{{ $profileUser->avatar ? asset('storage/avatars/' . $profileUser->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($profileUser->fullname).'&background=E5E5E5&size=128' }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                
                <p class="text-xs text-neutral-500 mb-4">Terakhir dilihat: Today, 01:00 am<br>1 Followers</p>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg> Ikuti</button>
                    <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg> Pesan</button>
                    <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah Teman</button>
                    <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg> Blokir</button>
                </div>

                @if(auth()->check() && auth()->user()->id === $profileUser->id)
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center w-full text-xs font-bold text-neutral-700 bg-white border border-neutral-200 py-2 rounded shadow-sm hover:bg-neutral-50 transition mb-4">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> EDIT PROFIL
                    </a>
                @endif

                <div class="grid grid-cols-3 gap-2 border-t border-neutral-100 pt-4">
                    <div><p class="text-sm font-bold text-neutral-800">12</p><p class="text-[10px] font-bold text-neutral-500 uppercase">Projects</p></div>
                    <div><p class="text-sm font-bold text-neutral-800">8</p><p class="text-[10px] font-bold text-neutral-500 uppercase">Certs</p></div>
                    <div><p class="text-sm font-bold text-neutral-800">34</p><p class="text-[10px] font-bold text-neutral-500 uppercase">Points</p></div>
                </div>

                <!-- Media X-CODE -->
                <div class="text-left mt-6 border-t border-neutral-100 pt-4">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider mb-3">MEDIA X-CODE</h4>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center text-xs text-neutral-600 hover:text-red-700 transition"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg> Website</a>
                        <a href="#" class="flex items-center text-xs text-neutral-600 hover:text-red-700 transition"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg> Forum</a>
                        <a href="#" class="flex items-center text-xs text-neutral-600 hover:text-red-700 transition"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg> Blog</a>
                    </div>
                </div>
            </div>

            <!-- Tentang Saya -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider mb-4">TENTANG SAYA</h4>
                
                @if($profileUser->about_me)
                <p class="text-xs text-neutral-600 mb-4 pb-4 border-b border-neutral-100 leading-relaxed">
                    {{ $profileUser->about_me }}
                </p>
                @endif

                <div class="space-y-3">
                    <div class="flex justify-between text-xs"><span class="text-neutral-500">Nama Lengkap</span> <span class="font-bold text-neutral-900">{{ $profileUser->fullname }}</span></div>
                    
                    @if($profileUser->country || $profileUser->location)
                    <div class="flex justify-between text-xs"><span class="text-neutral-500">Lokasi</span> <span class="font-bold text-neutral-900">{{ $profileUser->location ? $profileUser->location . ', ' : '' }}{{ $profileUser->country }}</span></div>
                    @endif
                    
                    <div class="flex justify-between text-xs">
                        <span class="text-neutral-500">Jenis Kelamin</span> 
                        <span class="font-bold text-neutral-900">
                            @if($profileUser->gender == 1) Laki-Laki @elseif($profileUser->gender == 2) Perempuan @else Tidak Disebutkan @endif
                        </span>
                    </div>
                    
                    @if(!$profileUser->hide_age && $profileUser->birthyear)
                    <div class="flex justify-between text-xs"><span class="text-neutral-500">Umur</span> <span class="font-bold text-neutral-900">{{ date('Y') - $profileUser->birthyear }} Tahun</span></div>
                    @endif
                </div>
            </div>
            
            <!-- Mengikuti & Teman -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">MENGIKUTI</h4>
                    <a href="#" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No following yet</div>
            </div>
        </div>

        <!-- KOLOM TENGAH (Feed Dinding / Menyukai / Foto / Video) -->
        <div class="lg:col-span-6 space-y-6">
            
            @if($tab === 'dinding')
                @if(auth()->check() && auth()->user()->id === $profileUser->id)
                <!-- Form Bagi Cepat -->
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                    <!-- Tabs -->
                    <div class="flex space-x-6 mb-4 border-b border-neutral-100 pb-2">
                        <button type="button" onclick="switchTab('status')" id="tab-btn-status" class="flex items-center text-xs font-bold text-red-700 pb-2 border-b-2 border-red-700 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> STATUS
                        </button>
                        <button type="button" onclick="switchTab('unggah')" id="tab-btn-unggah" class="flex items-center text-xs font-medium text-neutral-500 hover:text-red-700 pb-2 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> UNGGAH FOTO
                        </button>
                        <button type="button" onclick="switchTab('video')" id="tab-btn-video" class="flex items-center text-xs font-medium text-neutral-500 hover:text-red-700 pb-2 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> UNGGAH VIDEO
                        </button>
                    </div>

                    <form action="{{ route('stream.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- TAB CONTENT: STATUS -->
                        <div id="tab-content-status">
                            <textarea name="message" rows="3" placeholder="What's happening..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-red-700 transition resize-none"></textarea>
                        </div>

                        <!-- TAB CONTENT: UNGGAH -->
                        <div id="tab-content-unggah" class="hidden bg-neutral-50 p-4 border border-neutral-200 rounded-lg space-y-4">
                            {{-- Album ID akan terhubung ke sistem album yang dibuat Ipan --}}
                            <input type="hidden" name="album_id" id="photo-album-id" value="0">

                            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                                <label class="text-xs text-neutral-600 w-24">Pilih Foto:</label>
                                <input type="file" name="photo" id="photo-input-post" accept="image/*" onchange="previewPhoto(event)" class="flex-1 text-sm text-neutral-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            </div>
                            
                            <div class="flex justify-end items-center space-x-2 mt-2 pt-2 border-t border-neutral-200">
                                <label class="text-xs text-neutral-600">Privasi:</label>
                                <select name="privacy" class="bg-white border border-neutral-300 rounded px-2 py-1 text-xs focus:outline-none focus:border-red-700">
                                    <option value="public">Siapapun</option>
                                    <option value="friends">Teman</option>
                                    <option value="private">Hanya Saya</option>
                                </select>
                            </div>
                        </div>

                        <!-- TAB CONTENT: VIDEO -->
                        <div id="tab-content-video" class="hidden bg-neutral-50 p-4 border border-neutral-200 rounded-lg space-y-4">
                            <div class="text-xs font-medium text-red-600 mb-2">please insert a video URL</div>
                            {{-- Album video akan terhubung ke sistem album yang dibuat Ipan --}}
                            <input type="hidden" name="video_album_id" id="video-album-id" value="0">
                            
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                <label class="text-xs text-neutral-600 w-24">Judul Video:</label>
                                <input type="text" name="video_title" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700">
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start space-y-2 sm:space-y-0 sm:space-x-4">
                                <label class="text-xs text-neutral-600 w-24 mt-1">Deskripsi:</label>
                                <textarea name="video_desc" rows="2" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700 resize-none"></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                                <label class="text-xs text-neutral-600 w-24">tanda:</label>
                                <input type="text" name="video_tags" placeholder="(Dipisahkan dengan koma)" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700">
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                                <label class="text-xs text-neutral-600 w-24">Sumber video:</label>
                                <input type="text" name="video_url" placeholder="http://www.youtube.com/watch?v=" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700">
                            </div>
                            
                            <div class="flex justify-end items-center space-x-2 mt-2 pt-2 border-t border-neutral-200">
                                <label class="text-xs text-neutral-600">Privasi:</label>
                                <select name="video_privacy" class="bg-white border border-neutral-300 rounded px-2 py-1 text-xs focus:outline-none focus:border-red-700">
                                    <option value="public">Siapapun</option>
                                    <option value="friends">Teman</option>
                                    <option value="private">Hanya Saya</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="photo-preview-container" class="hidden mt-3 relative inline-block">
                            <img id="photo-preview" src="#" class="h-20 rounded-md border border-neutral-200 object-cover">
                            <button type="button" onclick="clearPhoto()" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        


                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-[#990000] text-white font-bold px-6 py-2 rounded shadow-sm text-sm hover:bg-red-800 transition">Bagikan</button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Loop Postingan (Dinding) -->
                @forelse ($streams as $stream)
                    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden border border-neutral-200">
                                    <img src="{{ $stream->user->avatar ? asset('storage/avatars/'.$stream->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($stream->user->fullname ?? 'Unknown').'&background=E5E5E5' }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-900">{{ $stream->user->fullname ?? 'Unknown User' }}</h4>
                                    <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}</p>
                                </div>
                            </div>
                            <button class="text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg></button>
                        </div>
                        
                        <p class="text-sm text-neutral-800 mb-4 whitespace-pre-wrap leading-relaxed">{{ $stream->message }}</p>

                        @if($stream->type == 2 && $stream->attachment)
                            @php $att = json_decode($stream->attachment, true); @endphp
                            @if(isset($att['photo']))
                            <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                <img src="{{ asset('storage/posts/' . $att['photo']) }}" class="w-full h-auto" alt="Post Photo">
                            </div>
                            @endif
                        @endif
                        
                        @if($stream->type == 3 && $stream->attachment)
                            @php $att = json_decode($stream->attachment, true); @endphp
                            @if(isset($att['video_url']))
                                @php
                                    $videoUrl = $att['video_url'];
                                    $embedUrl = '';
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @if($embedUrl)
                                        <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <a href="{{ $videoUrl }}" target="_blank" class="text-blue-600 hover:underline flex items-center p-3 bg-neutral-50"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Tonton Video</a>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-between items-center border-t border-neutral-100 pt-3">
                            <div class="flex space-x-4">
                                <form action="{{ route('like.toggle', $stream->id) }}" method="POST" class="form-like">
                                    @csrf
                                    <button type="submit" class="flex items-center text-xs font-semibold text-neutral-500 hover:text-red-700 transition">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> <span id="like-count-{{ $stream->id }}">{{ $stream->likes }}</span>&nbsp;Suka
                                    </button>
                                </form>
                                <button type="button" onclick="document.getElementById('comment-form-{{ $stream->id }}').classList.toggle('hidden')" class="flex items-center text-xs font-semibold text-neutral-500 hover:text-red-700 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> <span id="comments-count-{{ $stream->id }}">{{ $stream->comments->count() ?? 0 }}</span>&nbsp;Komentar
                                </button>
                            </div>
                            <button class="text-neutral-400 hover:text-neutral-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg></button>
                        </div>

                        <!-- Comment Section -->
                        <div id="comment-form-{{ $stream->id }}" class="hidden mt-3 pt-3 border-t border-neutral-100">
                            <!-- Loop Comments -->
                            <div id="comments-list-{{ $stream->id }}">
                                @foreach($stream->comments as $comment)
                                    <div class="flex items-start space-x-2 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->fullname ?? 'Unknown') }}&background=E5E5E5" class="w-full h-full">
                                        </div>
                                        <div class="bg-neutral-50 p-2.5 rounded-lg flex-1 text-sm">
                                            <span class="font-bold text-neutral-900">{{ $comment->user->fullname ?? 'Unknown' }}</span>
                                            @php 
                                                // Parse Mentions
                                                $parsedMessage = htmlspecialchars($comment->message);
                                                $parsedMessage = preg_replace('/@([a-zA-Z0-9_]+)/', '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>', $parsedMessage);
                                            @endphp
                                            <p class="text-neutral-700 mt-1">{!! $parsedMessage !!}</p>
                                            <button type="button" onclick="replyTo('{{ $stream->id }}', '{{ $comment->user->username ?? 'user' }}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 mt-1 uppercase tracking-wider">Reply</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Comment Form -->
                            @if(auth()->check())
                            <form action="{{ route('comment.store', $stream->id) }}" method="POST" class="flex space-x-2 form-comment" data-stream-id="{{ $stream->id }}">
                                @csrf
                                <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->fullname).'&background=E5E5E5' }}" class="w-full h-full">
                                </div>
                                <input type="text" name="message" id="comment-input-{{ $stream->id }}" required placeholder="Tulis komentar..." class="flex-1 bg-neutral-50 border border-neutral-200 rounded-full px-4 text-sm focus:outline-none focus:border-red-700">
                                <button type="submit" class="text-red-700 font-bold text-sm px-2">Kirim</button>
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Tidak ada postingan di dinding.</div>
                @endforelse
                
                <div class="mt-4">{{ $streams->links() }}</div>

            @elseif($tab === 'menyukai')
                <div class="bg-neutral-100 rounded-xl p-4 border border-neutral-200 mb-4">
                    <h3 class="text-xs font-bold text-neutral-700 uppercase tracking-wider">MENYUKAI</h3>
                </div>

                <!-- Loop Postingan (Menyukai) -->
                @forelse ($streams as $stream)
                    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden border border-neutral-200">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($stream->user->fullname ?? 'Unknown') }}&background=E5E5E5" class="w-full h-full">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-900">{{ $stream->user->fullname ?? 'Unknown User' }} <span class="font-normal text-neutral-500">Mendaftar / Menyukai</span></h4>
                                    <p class="text-[11px] text-neutral-400"><svg class="w-3 h-3 inline pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ \Carbon\Carbon::createFromTimestamp($stream->created)->format('M jS Y, g:i a') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-sm text-neutral-800 mb-4 whitespace-pre-wrap leading-relaxed">{{ $stream->message }}</p>

                        <div class="text-[11px] text-neutral-500 border-t border-neutral-100 pt-3">
                            <button class="text-red-700 hover:underline font-semibold">+Komentar</button> <span class="mx-1">|</span> <button class="text-red-700 hover:underline font-semibold">Suka</button>
                            <div class="mt-2 text-neutral-400">
                                <svg class="w-3 h-3 inline text-red-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> {{ $stream->likes }} orang menyukai ini
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Belum ada aktivitas yang disukai.</div>
                @endforelse
                
                <div class="mt-4">{{ $streams->links() }}</div>

            @elseif($tab === 'foto')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xs font-bold text-neutral-700 uppercase tracking-wider">FOTO</h3>
                        @if(auth()->check() && auth()->user()->id === $profileUser->id)
                            <a href="#" class="bg-[#990000] text-white text-[10px] font-bold px-4 py-2 rounded uppercase tracking-wider hover:bg-red-800 transition">ADD NEW PHOTO</a>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($photos as $photo)
                        <div class="group">
                            <div class="w-full h-40 bg-neutral-100 rounded-lg overflow-hidden border border-neutral-200 mb-3 relative">
                                <!-- Placeholder if $photo->thumbnail is empty -->
                                @if($photo->thumbnail)
                                    <img src="{{ asset('uploads/' . $photo->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-neutral-200 to-neutral-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-neutral-900 mb-1 truncate">{{ $photo->title ?: 'Untitled' }}</h4>
                            <p class="text-xs text-neutral-500">{{ $profileUser->fullname }}</p>
                        </div>
                        @empty
                        <div class="col-span-2 text-center text-sm text-neutral-500 py-10">Belum ada foto yang diunggah.</div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $photos->links() }}</div>
                </div>

            @elseif($tab === 'video')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xs font-bold text-neutral-700 uppercase tracking-wider">VIDEO</h3>
                        @if(auth()->check() && auth()->user()->id === $profileUser->id)
                            <a href="#" class="bg-[#990000] text-white text-[10px] font-bold px-4 py-2 rounded uppercase tracking-wider hover:bg-red-800 transition">ADD NEW VIDEO</a>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($videos as $video)
                        <div class="group">
                            <div class="w-full h-40 bg-neutral-100 rounded-lg overflow-hidden border border-neutral-200 mb-3 relative">
                                <!-- Placeholder if $video->thumbnail is empty -->
                                @if($video->thumbnail)
                                    <img src="{{ asset('uploads/' . $video->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-neutral-200 to-neutral-300 flex items-center justify-center relative">
                                        <svg class="w-8 h-8 text-neutral-400 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-700 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-sm font-bold text-neutral-900 mb-1 truncate">{{ $video->title ?: 'Untitled Video' }}</h4>
                            <p class="text-xs text-neutral-500">{{ $profileUser->fullname }}</p>
                        </div>
                        @empty
                        <div class="col-span-2 text-center text-sm text-neutral-500 py-10">Belum ada video yang diunggah.</div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $videos->links() }}</div>
                </div>

            @endif

        </div>

        <!-- KOLOM KANAN (Widget) -->
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
                            <!-- LinkedIn SVG -->
                            <img src="{{ asset('assets/img/logo-linkedin.png') }}" class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100 transition"> LinkedIn
                        </div>
                        <span class="text-blue-500 font-bold">&rarr;</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                        <div class="flex items-center text-sm font-medium text-neutral-600">
                            <!-- phpBB SVG -->
                            <img src="{{ asset('assets/img/logo-phpbb.png') }}" class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100 transition"> phpBB Group
                        </div>
                        <span class="text-blue-500 font-bold">&rarr;</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                        <div class="flex items-center text-sm font-medium text-neutral-600">
                            <!-- Facebook SVG -->
                            <svg class="w-4 h-4 mr-3 text-neutral-400 group-hover:text-[#1877F2] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg> Facebook
                        </div>
                        <span class="text-blue-500 font-bold">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(tabName) {
    // Sembunyikan semua konten tab
    document.getElementById('tab-content-status').classList.add('hidden');
    document.getElementById('tab-content-unggah').classList.add('hidden');
    document.getElementById('tab-content-video').classList.add('hidden');

    // Kembalikan gaya tombol tab ke default (abu-abu)
    const tabs = ['status', 'unggah', 'video'];
    tabs.forEach(t => {
        const btn = document.getElementById('tab-btn-' + t);
        btn.classList.remove('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
        btn.classList.add('text-neutral-500', 'font-medium');
    });

    // Tampilkan konten tab yang aktif
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');

    // Ubah gaya tombol tab yang aktif (merah dan bold)
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.classList.remove('text-neutral-500', 'font-medium');
    activeBtn.classList.add('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
}

function previewPhoto(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-preview').src = e.target.result;
            document.getElementById('photo-preview-container').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function clearPhoto() {
    document.getElementById('photo-input-post').value = '';
    document.getElementById('photo-preview-container').classList.add('hidden');
    document.getElementById('photo-preview').src = '#';
}



// Function to handle Reply
function replyTo(streamId, username) {
    // Show comment form if hidden
    const commentForm = document.getElementById('comment-form-' + streamId);
    if(commentForm && commentForm.classList.contains('hidden')) {
        commentForm.classList.remove('hidden');
    }
    const input = document.getElementById('comment-input-' + streamId);
    input.value = '@' + username + ' ';
    input.focus();
}

// AJAX Handling
document.addEventListener('DOMContentLoaded', function() {
    
    // AJAX for Likes
    document.querySelectorAll('.form-like').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);
            const streamId = url.split('/').pop();

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('like-count-' + streamId).textContent = data.likes;
                const btn = this.querySelector('button');
                if(data.status === 'liked') {
                    btn.classList.add('text-red-700');
                } else {
                    btn.classList.remove('text-red-700');
                }
            });
        });
    });

    // AJAX for Comments
    document.querySelectorAll('.form-comment').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);
            const streamId = this.dataset.streamId;
            const inputField = this.querySelector('input[name="message"]');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update comment count
                    document.getElementById('comments-count-' + streamId).textContent = data.comments_count;
                    
                    // Parse Mentions in JS
                    let parsedMessage = data.comment.message;
                    // Escape HTML basic
                    parsedMessage = parsedMessage.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                    // Regex for Mention
                    parsedMessage = parsedMessage.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>');

                    // Append new comment HTML
                    const commentHtml = `
                        <div class="flex items-start space-x-2 mb-3">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                <img src="${data.comment.user.avatar}" class="w-full h-full">
                            </div>
                            <div class="bg-neutral-50 p-2.5 rounded-lg flex-1 text-sm">
                                <span class="font-bold text-neutral-900">${data.comment.user.fullname}</span>
                                <p class="text-neutral-700 mt-1">${parsedMessage}</p>
                                <button type="button" onclick="replyTo('${streamId}', '${data.comment.user.username}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 mt-1 uppercase tracking-wider">Reply</button>
                            </div>
                        </div>
                    `;
                    document.getElementById('comments-list-' + streamId).insertAdjacentHTML('beforeend', commentHtml);
                    
                    // Clear input
                    inputField.value = '';
                }
            });
        });
    });
});
</script>
@endsection