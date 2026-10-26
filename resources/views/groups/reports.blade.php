@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2"><a href="{{ route('groups.show', $group->id) }}" class="hover:text-red-600 transition-colors">GRUP</a> > <a href="{{ route('groups.show', $group->id) }}" class="hover:text-red-600 transition-colors">{{ strtoupper($group->name) }}</a> > LAPORAN</div>
                <h1 class="text-3xl font-black text-gray-900">LAPORAN GRUP</h1>
            </div>
            <div>
                <a href="{{ route('groups.show', $group->id) }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors">KEMBALI KE GRUP</a>
            </div>
        </div>

        

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                    User Reports
                </h2>
                <div class="text-sm font-medium text-gray-500">
                    Total: {{ $reports->count() }} Laporan
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 font-bold">Status</th>
                            <th class="px-6 py-4 font-bold">Pelapor</th>
                            <th class="px-6 py-4 font-bold">URL Terkait</th>
                            <th class="px-6 py-4 font-bold">Alasan</th>
                            <th class="px-6 py-4 font-bold">Tanggal</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors {{ $report->hasread ? 'opacity-70' : '' }}">
                            <td class="px-6 py-4">
                                @if($report->hasread)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $report->user?->avatar_url ?? asset('assets/img/default.png') }}" 
                                         class="w-8 h-8 rounded-full border border-gray-200 object-cover"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';">
                                    <span class="font-bold text-sm text-gray-900">{{ $report->user?->fullname ?? $report->user?->username ?? 'Unknown User' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $contentId = null;
                                    $content = null;
                                    $isComment = false;
                                    $isPostOrComment = false;
                                    $isPhoto = false;

                                    if (preg_match('/#comment-(\d+)/', $report->url, $m)) {
                                        $contentId = (int) $m[1];
                                        $isComment = true;
                                        $isPostOrComment = true;
                                        $content = \App\Models\Comment::with('user')->find($contentId);
                                    } elseif (preg_match('/#post-(\d+)/', $report->url, $m)) {
                                        $contentId = (int) $m[1];
                                        $isPostOrComment = true;
                                        $content = \App\Models\Stream::with('user')->find($contentId);
                                    } elseif (preg_match('/\/stream\/(\d+)/', $report->url, $m)) {
                                        $contentId = (int) $m[1];
                                        $isPostOrComment = true;
                                        $content = \App\Models\Stream::with('user')->find($contentId);
                                    } elseif (preg_match('/#(\d+)/', $report->url, $m)) {
                                        $contentId = (int) $m[1];
                                        $isPostOrComment = true;
                                        $content = \App\Models\Comment::with('user')->find($contentId);
                                        if ($content) {
                                            $isComment = true;
                                        } else {
                                            $content = \App\Models\Stream::with('user')->find($contentId);
                                        }
                                    } elseif (preg_match('/\/foto(?:s)?\/(\d+)/', $report->url, $m) || preg_match('/\/photo(?:s)?\/(\d+)/', $report->url, $m)) {
                                        $contentId = (int) $m[1];
                                        $isPhoto = true;
                                        $isPostOrComment = true;
                                        $content = \App\Models\Photo::with('album.user')->find($contentId);
                                    }

                                    $photoUrls = [];
                                    $ytEmbed = null;
                                    $authorName = 'Unknown User';
                                    $authorAvatar = asset('assets/img/default.png');
                                    $timeAgo = '-';
                                    $contentMessage = '';
                                    $deleteRoute = null;
                                    $contentTypeLabel = 'Konten';

                                    if ($content) {
                                        if ($isComment) {
                                            $contentTypeLabel = 'Komentar';
                                            $deleteRoute = route('comment.destroy', $content->id);
                                            $authorName = $content->user?->fullname ?? $content->user?->username ?? 'Unknown';
                                            $authorAvatar = $content->user?->avatar_url ?? asset('assets/img/default.png');
                                            $timeAgo = $content->created ? \Carbon\Carbon::createFromTimestamp($content->created)->diffForHumans() : '-';
                                            $contentMessage = $content->message;
                                        } elseif ($isPhoto) {
                                            $contentTypeLabel = 'Foto';
                                            if (\Illuminate\Support\Facades\Route::has('foto.photo.destroy')) {
                                                $deleteRoute = route('foto.photo.destroy', $content->id);
                                            }
                                            $u = $content->album?->user;
                                            $authorName = $u?->fullname ?? $u?->username ?? 'Unknown';
                                            $authorAvatar = $u?->avatar_url ?? asset('assets/img/default.png');
                                            $contentMessage = $content->des;
                                            if (!empty($content->photo_url)) {
                                                $photoUrls[] = $content->photo_url;
                                            }
                                        } else {
                                            $contentTypeLabel = 'Postingan';
                                            $deleteRoute = route('stream.destroy', $content->id);
                                            $authorName = $content->user?->fullname ?? $content->user?->username ?? 'Unknown';
                                            $authorAvatar = $content->user?->avatar_url ?? asset('assets/img/default.png');
                                            $timeAgo = $content->created ? \Carbon\Carbon::createFromTimestamp($content->created)->diffForHumans() : '-';
                                            $contentMessage = $content->message;
                                            $photoUrls = $content->photo_urls ?? [];

                                            if ($content->attachment) {
                                                if (str_starts_with($content->attachment, 'youtube:')) {
                                                    $ytEmbed = 'https://www.youtube.com/embed/' . str_replace('youtube:', '', $content->attachment);
                                                } else {
                                                    $attJson = $content->parsed_attachment;
                                                    if (!empty($attJson['video_url']) && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $attJson['video_url'], $matchesYt)) {
                                                        $ytEmbed = 'https://www.youtube.com/embed/' . $matchesYt[1];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @if($content)
                                    <div x-data="{ openPostModal: false }">
                                        <button type="button" @click="openPostModal = true" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                            Lihat {{ $contentTypeLabel }} <i data-lucide="external-link" class="w-3 h-3"></i>
                                        </button>
                                        
                                        <!-- Modal Postingan/Komentar/Foto -->
                                        <div x-show="openPostModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-transition>
                                            <div @click.away="openPostModal = false" class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden relative">
                                                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                    <h3 class="font-bold text-gray-900">Konten yang Dilaporkan ({{ $contentTypeLabel }})</h3>
                                                    <button type="button" @click="openPostModal = false" class="text-gray-400 hover:text-gray-600">
                                                        <i data-lucide="x" class="w-5 h-5"></i>
                                                    </button>
                                                </div>
                                                <div class="p-5 max-h-[60vh] overflow-y-auto">
                                                    <div class="flex items-start gap-3 mb-3">
                                                        <img src="{{ $authorAvatar }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                                        <div>
                                                            <span class="font-bold text-gray-900 text-sm">{{ $authorName }}</span>
                                                            <p class="text-xs text-gray-400">{{ $timeAgo }}</p>
                                                        </div>
                                                    </div>
                                                    @if($contentMessage)
                                                        <p class="text-gray-700 text-sm mb-3 whitespace-pre-wrap">{{ $contentMessage }}</p>
                                                    @endif

                                                    @if(count($photoUrls) > 0)
                                                        <div class="space-y-3 mt-3">
                                                            @foreach($photoUrls as $pUrl)
                                                                <a href="{{ $pUrl }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 bg-gray-50 hover:opacity-95 transition" title="Klik untuk membuka ukuran penuh">
                                                                    <img src="{{ $pUrl }}" class="w-full max-h-80 object-contain rounded-xl" alt="Foto Laporan">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if($ytEmbed)
                                                        <div class="mt-3 rounded-xl overflow-hidden border border-gray-200">
                                                            <iframe class="w-full aspect-video rounded-xl" src="{{ $ytEmbed }}" frameborder="0" allowfullscreen></iframe>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                                                    @if($deleteRoute)
                                                        <form action="{{ $deleteRoute }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Hapus {{ strtolower($contentTypeLabel) }} ini secara permanen?')" class="text-xs font-bold bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg transition-colors border border-red-200">
                                                                Hapus {{ $contentTypeLabel }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($isPostOrComment)
                                    <span class="text-sm font-medium text-gray-400 italic flex items-center gap-1">
                                        <i data-lucide="info" class="w-3 h-3"></i> Konten telah dihapus
                                    </span>
                                @else
                                    <a href="{{ $report->url }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1 break-all max-w-xs">
                                        Lihat URL / Grup <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 max-w-md line-clamp-2" title="{{ $report->message }}">
                                    {{ $report->message }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ \Carbon\Carbon::createFromTimestamp($report->created)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(!$report->hasread)
                                <form action="{{ route('groups.reports.resolve', ['group' => $group->id, 'id' => $report->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-bold bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg transition-colors border border-green-200">
                                        Tandai Selesai
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i data-lucide="check-circle" class="w-12 h-12 mb-3 text-green-400"></i>
                                    <p class="text-base font-bold text-gray-600">Hore! Tidak ada laporan.</p>
                                    <p class="text-sm mt-1">Komunitas saat ini aman dan bersih.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
