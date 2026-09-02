@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2"><a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition-colors">HOME</a> > <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition-colors">ADMIN CP</a> > REPORTS</div>
                <h1 class="text-3xl font-black text-gray-900">REPORTS & MODERATION</h1>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors">BACK TO DASHBOARD</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

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
                                    <img src="{{ $report->user?->avatar ? asset('storage/'.$report->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($report->user?->username ?? 'User').'&background=random&color=fff' }}" 
                                         class="w-8 h-8 rounded-full border border-gray-200">
                                    <span class="font-bold text-sm text-gray-900">{{ $report->user?->username ?? 'Unknown User' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $contentId = null;
                                    $content = null;
                                    $isComment = false;
                                    $isPostOrComment = false;
                                    if (str_contains($report->url, '#post-')) {
                                        $parts = explode('#post-', $report->url);
                                        $contentId = $parts[1] ?? null;
                                        $isPostOrComment = true;
                                    } elseif (str_contains($report->url, '#comment-')) {
                                        $parts = explode('#comment-', $report->url);
                                        $contentId = $parts[1] ?? null;
                                        $isComment = true;
                                        $isPostOrComment = true;
                                    }
                                    if ($contentId) {
                                        if ($isComment) {
                                            $content = \App\Models\Comment::find($contentId);
                                        } else {
                                            $content = \App\Models\Stream::find($contentId);
                                        }
                                    }
                                @endphp

                                @if($content)
                                    <div x-data="{ openPostModal: false }">
                                        <button type="button" @click="openPostModal = true" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                            Lihat {{ $isComment ? 'Komentar' : 'Postingan' }} <i data-lucide="external-link" class="w-3 h-3"></i>
                                        </button>
                                        
                                        <!-- Modal Postingan/Komentar -->
                                        <div x-show="openPostModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-transition>
                                            <div @click.away="openPostModal = false" class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden relative">
                                                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                    <h3 class="font-bold text-gray-900">Konten yang Dilaporkan</h3>
                                                    <button type="button" @click="openPostModal = false" class="text-gray-400 hover:text-gray-600">
                                                        <i data-lucide="x" class="w-5 h-5"></i>
                                                    </button>
                                                </div>
                                                <div class="p-5 max-h-[50vh] overflow-y-auto">
                                                    <div class="flex items-start gap-3 mb-3">
                                                        <img src="{{ $content->user?->avatar ? asset('storage/'.$content->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($content->user?->username ?? 'User').'&background=random&color=fff' }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                                        <div>
                                                            <span class="font-bold text-gray-900 text-sm">{{ $content->user?->username ?? 'Unknown' }}</span>
                                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::createFromTimestamp($content->created)->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                    @if($content->message)
                                                        <p class="text-gray-700 text-sm mb-3 whitespace-pre-wrap">{{ $content->message }}</p>
                                                    @endif
                                                    @if(!$isComment && $content->attachment)
                                                        @if(str_starts_with($content->attachment, 'youtube:'))
                                                            @php $ytId = str_replace('youtube:', '', $content->attachment); @endphp
                                                            <iframe class="w-full aspect-video rounded-xl mt-2" src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0"></iframe>
                                                        @else
                                                            <img src="{{ asset('storage/'.$content->attachment) }}" class="w-full rounded-xl max-h-64 object-contain bg-gray-50 mt-2 border border-gray-100">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                                                    <form action="{{ $isComment ? route('comment.destroy', $content->id) : route('stream.destroy', $content->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus konten ini secara permanen?')" class="text-xs font-bold bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg transition-colors border border-red-200">
                                                            Hapus {{ $isComment ? 'Komentar' : 'Postingan' }}
                                                        </button>
                                                    </form>
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
                                @php
                                    $groupName = null;
                                    if (str_contains($report->url, '/groups/')) {
                                        preg_match('/\/groups\/(\d+)/', $report->url, $matches);
                                        $groupId = $matches[1] ?? null;
                                        if ($groupId) {
                                            $g = \App\Models\Group::find($groupId);
                                            if ($g) $groupName = $g->name;
                                        }
                                    }
                                @endphp
                                <p class="text-sm text-gray-700 max-w-md line-clamp-2" title="{{ $report->message }}">
                                    {{ $report->message ?: '-' }}
                                </p>
                                @if($groupName)
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <i data-lucide="users" class="w-3 h-3"></i> Grup: {{ $groupName }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ \Carbon\Carbon::createFromTimestamp($report->created)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(!$report->hasread)
                                <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" class="inline">
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
