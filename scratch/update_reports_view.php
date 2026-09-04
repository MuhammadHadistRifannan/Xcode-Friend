<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/admin/reports.blade.php';
$content = file_get_contents($file);

$searchParsing = <<<'EOD'
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
EOD;

$replaceParsing = <<<'EOD'
                                @php
                                    $contentId = null;
                                    $content = null;
                                    $isComment = false;
                                    $isPostOrComment = false;
                                    
                                    if (str_contains($report->url, '/stream/')) {
                                        $parts = explode('/stream/', $report->url);
                                        $contentId = $parts[1] ?? null;
                                        $isPostOrComment = true;
                                    } elseif (str_contains($report->url, '#')) {
                                        $parts = explode('#', $report->url);
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
                                            Lihat Unggah <i data-lucide="external-link" class="w-3 h-3"></i>
                                        </button>
EOD;

$content = str_replace($searchParsing, $replaceParsing, $content);

// 2. Add Delete Button
$searchAksi = <<<'EOD'
                                    <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-bold bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg transition-colors">
                                            Tandai Selesai
                                        </button>
                                    </form>
EOD;

$replaceAksi = <<<'EOD'
                                    <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-bold bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg transition-colors w-full mb-2">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus laporan ini secara permanen?')" class="text-xs font-bold bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg transition-colors w-full">
                                            Hapus
                                        </button>
                                    </form>
EOD;

$content = str_replace($searchAksi, $replaceAksi, $content);

file_put_contents($file, $content);
echo "Reports view updated.\n";
