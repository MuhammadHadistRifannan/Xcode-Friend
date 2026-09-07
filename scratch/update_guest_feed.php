<?php
$guest_file = 'resources/views/home/guest.blade.php';
$content = file_get_contents($guest_file);

$media_snippet = <<<'EOD'
            <div class="pl-13 mb-4">
                <p class="text-sm text-neutral-700 whitespace-pre-wrap mb-4">{{ $stream->message }}</p>

                @if($stream->type == 2 && $stream->attachment)
                    @php $att = json_decode($stream->attachment, true); @endphp
                    @if(isset($att['photos']) && is_array($att['photos']))
                        @php 
                            $ptCount = count($att['photos']); 
                            $photoUrls = array_map(fn($p) => asset('storage/posts/' . $p), $att['photos']);
                        @endphp
                        <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                            @if($ptCount == 1)
                                <img src="{{ $photoUrls[0] }}" class="w-full h-auto max-h-[500px] object-cover" alt="Post Photo">
                            @elseif($ptCount == 2)
                                <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                                    <img src="{{ $photoUrls[0] }}" class="w-full h-full object-cover" alt="Post Photo">
                                    <img src="{{ $photoUrls[1] }}" class="w-full h-full object-cover" alt="Post Photo">
                                </div>
                            @elseif($ptCount == 3)
                                <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                                    <img src="{{ $photoUrls[0] }}" class="w-full h-full object-cover" alt="Post Photo">
                                    <div class="grid grid-rows-2 gap-1 h-full">
                                        <img src="{{ $photoUrls[1] }}" class="w-full h-full object-cover" alt="Post Photo">
                                        <img src="{{ $photoUrls[2] }}" class="w-full h-full object-cover" alt="Post Photo">
                                    </div>
                                </div>
                            @elseif($ptCount >= 4)
                                <div class="grid grid-cols-2 grid-rows-2 gap-1 h-72 sm:h-96">
                                    <img src="{{ $photoUrls[0] }}" class="w-full h-full object-cover" alt="Post Photo">
                                    <img src="{{ $photoUrls[1] }}" class="w-full h-full object-cover" alt="Post Photo">
                                    <img src="{{ $photoUrls[2] }}" class="w-full h-full object-cover" alt="Post Photo">
                                    <div class="relative w-full h-full">
                                        <img src="{{ $photoUrls[3] }}" class="w-full h-full object-cover" alt="Post Photo">
                                        @if($ptCount > 4)
                                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                                <span class="text-white text-3xl font-bold">+{{ $ptCount - 4 }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif(isset($att['photo']))
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
            </div>
EOD;

$old = <<<'EOD'
            <div class="pl-13 mb-4">
                <p class="text-sm text-neutral-700 whitespace-pre-wrap">{{ $stream->message }}</p>
            </div>
EOD;

$content = str_replace($old, $media_snippet, $content);
file_put_contents($guest_file, $content);
echo "Updated guest.blade.php\n";
