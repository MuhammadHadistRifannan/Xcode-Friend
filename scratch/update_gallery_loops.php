<?php

function updateGalleryViews($file) {
    $content = file_get_contents($file);

    // Replace the block in media.blade.php
    $oldMediaBlock = <<<EOD
                    @php
                        \$firstPhoto = \$media->attachment;
                        if (\$media->type == 2) {
                            \$att = json_decode(\$media->attachment, true);
                            if (is_array(\$att) && isset(\$att['photos']) && count(\$att['photos']) > 0) {
                                \$firstPhoto = 'posts/' . \$att['photos'][0];
                            } elseif (is_array(\$att) && isset(\$att['photo'])) {
                                \$firstPhoto = 'posts/' . \$att['photo'];
                            }
                        }
                    @endphp
                    <!-- Photo Card -->
                    <div @click="photoUrl = '{{ asset('storage/'.\$firstPhoto) }}'" class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer">
                        <img src="{{ asset('storage/'.\$firstPhoto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <!-- Author Info (Bottom) -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <div class="flex items-center gap-2">
                                <img src="{{ \$media->user->avatar_url }}" class="w-5 h-5 rounded-full border border-white shrink-0">
                                <span class="text-white text-xs font-medium truncate drop-shadow-md">{{ \$media->user?->fullname ?? \$media->user?->username ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>
EOD;

    $newMediaBlock = <<<EOD
                    @php
                        \$photosArray = [];
                        if (\$media->type == 2) {
                            \$att = json_decode(\$media->attachment, true);
                            if (is_array(\$att)) {
                                if (isset(\$att['photos'])) {
                                    foreach(\$att['photos'] as \$p) \$photosArray[] = 'posts/' . \$p;
                                } elseif (isset(\$att['photo'])) {
                                    \$photosArray[] = 'posts/' . \$att['photo'];
                                }
                            } else {
                                \$photosArray[] = \$media->attachment;
                            }
                        } else {
                            \$photosArray[] = \$media->attachment;
                        }
                    @endphp
                    @foreach(\$photosArray as \$photoFile)
                    <!-- Photo Card -->
                    <div @click="photoUrl = '{{ asset('storage/'.\$photoFile) }}'" class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer">
                        <img src="{{ asset('storage/'.\$photoFile) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <!-- Author Info (Bottom) -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <div class="flex items-center gap-2">
                                <img src="{{ \$media->user->avatar_url }}" class="w-5 h-5 rounded-full border border-white shrink-0">
                                <span class="text-white text-xs font-medium truncate drop-shadow-md">{{ \$media->user?->fullname ?? \$media->user?->username ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
EOD;

    // Replace the block in show.blade.php sidebar
    $oldShowBlock = <<<EOD
                            @foreach(\$recentPhotos as \$photo)
                                @php
                                    \$firstPhoto = \$photo->attachment;
                                    if (\$photo->type == 2) {
                                        \$att = json_decode(\$photo->attachment, true);
                                        if (is_array(\$att) && isset(\$att['photos']) && count(\$att['photos']) > 0) {
                                            \$firstPhoto = 'posts/' . \$att['photos'][0];
                                        } elseif (is_array(\$att) && isset(\$att['photo'])) {
                                            \$firstPhoto = 'posts/' . \$att['photo'];
                                        }
                                    }
                                @endphp
                                <div @click="photoUrl = '{{ asset('storage/'.\$firstPhoto) }}'" class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer">
                                    <img src="{{ asset('storage/'.\$firstPhoto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @endforeach
EOD;

    $newShowBlock = <<<EOD
                            @php \$renderedPhotosCount = 0; @endphp
                            @foreach(\$recentPhotos as \$photoStream)
                                @php
                                    \$photosArray = [];
                                    if (\$photoStream->type == 2) {
                                        \$att = json_decode(\$photoStream->attachment, true);
                                        if (is_array(\$att)) {
                                            if (isset(\$att['photos'])) {
                                                foreach(\$att['photos'] as \$p) \$photosArray[] = 'posts/' . \$p;
                                            } elseif (isset(\$att['photo'])) {
                                                \$photosArray[] = 'posts/' . \$att['photo'];
                                            }
                                        } else {
                                            \$photosArray[] = \$photoStream->attachment;
                                        }
                                    } else {
                                        \$photosArray[] = \$photoStream->attachment;
                                    }
                                @endphp
                                @foreach(\$photosArray as \$photoFile)
                                    @if(\$renderedPhotosCount < 9)
                                    <div @click="photoUrl = '{{ asset('storage/'.\$photoFile) }}'" class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer">
                                        <img src="{{ asset('storage/'.\$photoFile) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                    @php \$renderedPhotosCount++; @endphp
                                    @endif
                                @endforeach
                            @endforeach
EOD;

    $content = str_replace($oldMediaBlock, $newMediaBlock, $content);
    $content = str_replace($oldShowBlock, $newShowBlock, $content);

    file_put_contents($file, $content);
}

updateGalleryViews('resources/views/groups/media.blade.php');
updateGalleryViews('resources/views/pages/media.blade.php');
updateGalleryViews('resources/views/groups/show.blade.php');
updateGalleryViews('resources/views/pages/show.blade.php');

echo "Update complete\n";
