@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="grid grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Undang Teman</h1>
        <p class="text-gray-500">Ajak teman-teman Anda bergabung ke dalam platform dan bangun komunitas bersama!</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Card with Alpine Tabs -->
    <div x-data="{ tab: 'share', copySuccess: false }" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Tabs Navigation -->
        <div class="flex border-b border-gray-100">
            <button @click="tab = 'share'" :class="{'text-red-600 border-b-2 border-red-600': tab === 'share', 'text-gray-500 hover:text-gray-700': tab !== 'share'}" class="flex-1 py-4 font-bold text-sm transition-colors outline-none focus:outline-none">
                Bagikan
            </button>
            <button @click="tab = 'email'" :class="{'text-red-600 border-b-2 border-red-600': tab === 'email', 'text-gray-500 hover:text-gray-700': tab !== 'email'}" class="flex-1 py-4 font-bold text-sm transition-colors outline-none focus:outline-none">
                Kirim Permohonan
            </button>
            <button @click="tab = 'history'" :class="{'text-red-600 border-b-2 border-red-600': tab === 'history', 'text-gray-500 hover:text-gray-700': tab !== 'history'}" class="flex-1 py-4 font-bold text-sm transition-colors outline-none focus:outline-none">
                Sejarah
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-8">
            
            <!-- TAB 1: Bagikan -->
            <div x-show="tab === 'share'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Link Undangan Anda</h3>
                
                <div class="flex items-center gap-3 mb-8">
                    <input type="text" id="invite-url" readonly value="{{ $inviteUrl }}" class="flex-1 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block w-full p-3 outline-none select-all transition-colors">
                    
                    <button @click="navigator.clipboard.writeText(document.getElementById('invite-url').value); copySuccess = true; setTimeout(() => copySuccess = false, 2000)" class="bg-gray-900 hover:bg-black text-white px-5 py-3 rounded-xl font-semibold text-sm transition-colors whitespace-nowrap flex items-center gap-2 shadow-sm">
                        <span x-show="!copySuccess">Copy Link</span>
                        <span x-show="copySuccess" class="text-green-400" style="display: none;">Copied!</span>
                        <svg x-show="!copySuccess" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <svg x-show="copySuccess" style="display: none;" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <p class="text-sm font-semibold text-gray-500 mb-4 uppercase tracking-wider text-center">Bagikan Ke Sosial Media</p>
                    <div class="flex justify-center gap-4">
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text=Halo! Gabung ke platform seru ini melalui link: {{ urlencode($inviteUrl) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full transition-transform hover:scale-105 shadow-sm">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($inviteUrl) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full transition-transform hover:scale-105 shadow-sm">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        
                        <!-- Twitter / X -->
                        <a href="https://twitter.com/intent/tweet?text=Gabung%20bersama%20kami!&url={{ urlencode($inviteUrl) }}" target="_blank" class="bg-black hover:bg-gray-800 text-white p-4 rounded-full transition-transform hover:scale-105 shadow-sm">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Kirim Permohonan (Email) -->
            <div x-show="tab === 'email'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Kirim Undangan via Email</h3>
                <p class="text-sm text-gray-500 mb-6">Masukkan alamat email teman Anda. Pisahkan dengan tanda koma (,) untuk mengirim ke banyak email sekaligus (Maksimal 5).</p>
                
                <form action="{{ route('invitation.email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="emails" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                        <textarea name="emails" id="emails" rows="4" placeholder="contoh1@email.com, contoh2@email.com" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-4 outline-none transition-colors shadow-sm resize-none" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                        <span>Kirim Undangan</span>
                        <svg class="w-5 h-5 transform rotate-45 -mt-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>

            <!-- TAB 3: Sejarah -->
            <div x-show="tab === 'history'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Undangan Email</h3>
                    <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-bold">{{ $histories->count() }} Undangan</span>
                </div>
                
                @if($histories->count() > 0)
                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email Tujuan</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Dikirim</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach($histories as $history)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $history->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::createFromTimestamp($history->created)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($history->status == 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Terkirim</span>
                                        @elseif($history->status == 1)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Diterima</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-100 border-dashed">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Belum ada riwayat undangan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai undang teman-teman Anda menggunakan form email.</p>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
        </div>
        
        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            <x-sidebar-right />
        </div>
    </div>
</div>
@endsection
