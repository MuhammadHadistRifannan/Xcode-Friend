<?php
function replaceMediaRendering($file) {
    $content = file_get_contents($file);

    // Photo Card Replacement (for media.blade.php loops where variable is $media)
    $oldPhotoCard = <<<EOD
                @if(\$type === 'photo')
                    <!-- Photo Card -->
                    <div @click="photoUrl = '{{ asset('storage/'.\$media->attachment) }}'" class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer">
                        <img src="{{ asset('storage/'.\$media->attachment) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
EOD;
    $newPhotoCard = <<<EOD
                @if(\$type === 'photo')
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
EOD;

    // Video Card Replacement (for media.blade.php loops where variable is $media)
    $oldVideoCard = <<<EOD
                @elseif(\$type === 'video')
                    <!-- Video Card -->
                    @php \$ytId = str_replace('youtube:', '', \$media->attachment); @endphp
                    <div class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer" @click="openVideo('https://www.youtube.com/watch?v={{ \$ytId }}')">
                        <img src="https://img.youtube.com/vi/{{ \$ytId }}/hqdefault.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
EOD;
    $newVideoCard = <<<EOD
                @elseif(\$type === 'video')
                    <!-- Video Card -->
                    @php
                        \$ytId = '';
                        if (\$media->type == 3) {
                            \$att = json_decode(\$media->attachment, true);
                            if (is_array(\$att) && isset(\$att['video_url'])) {
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', \$att['video_url'], \$matches)) {
                                    \$ytId = \$matches[1];
                                }
                            }
                        } elseif (str_starts_with(\$media->attachment, 'youtube:')) {
                            \$ytId = str_replace('youtube:', '', \$media->attachment);
                        }
                    @endphp
                    @if(\$ytId)
                    <div class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer" @click="openVideo('https://www.youtube.com/watch?v={{ \$ytId }}')">
                        <img src="https://img.youtube.com/vi/{{ \$ytId }}/hqdefault.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
EOD;

    // Apply replacements for media.blade.php
    $content = str_replace($oldPhotoCard, $newPhotoCard, $content);
    $content = str_replace($oldVideoCard, $newVideoCard, $content);

    // Also need to add an @endif if $ytId was conditionally wrapped, but wait, the existing video block doesn't have an @endif for ytId. 
    // Let me manually edit media.blade.php so it's precise.

    // Photo Sidebar Replacement (for show.blade.php loops where variable is $photo)
    $oldPhotoSidebar = <<<EOD
                                <div @click="photoUrl = '{{ asset('storage/'.\$photo->attachment) }}'" class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer">
                                    <img src="{{ asset('storage/'.\$photo->attachment) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
EOD;
    $newPhotoSidebar = <<<EOD
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
EOD;

    // Video Sidebar Replacement (for show.blade.php loops where variable is $video)
    $oldVideoSidebar = <<<EOD
                                @php \$ytId = str_replace('youtube:', '', \$video->attachment); @endphp
                                <div class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer" @click="openVideo('https://www.youtube.com/watch?v={{ \$ytId }}')">
                                    <img src="https://img.youtube.com/vi/{{ \$ytId }}/hqdefault.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
EOD;
    $newVideoSidebar = <<<EOD
                                @php
                                    \$ytId = '';
                                    if (\$video->type == 3) {
                                        \$att = json_decode(\$video->attachment, true);
                                        if (is_array(\$att) && isset(\$att['video_url'])) {
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', \$att['video_url'], \$matches)) {
                                                \$ytId = \$matches[1];
                                            }
                                        }
                                    } elseif (str_starts_with(\$video->attachment, 'youtube:')) {
                                        \$ytId = str_replace('youtube:', '', \$video->attachment);
                                    }
                                @endphp
                                @if(\$ytId)
                                <div class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer" @click="openVideo('https://www.youtube.com/watch?v={{ \$ytId }}')">
                                    <img src="https://img.youtube.com/vi/{{ \$ytId }}/hqdefault.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
EOD;
    $oldVideoEnd = <<<EOD
                                    </div>
                                </div>
EOD;
    $newVideoEnd = <<<EOD
                                    </div>
                                </div>
                                @endif
EOD;

    $content = str_replace($oldPhotoSidebar, $newPhotoSidebar, $content);
    $content = str_replace($oldVideoSidebar, $newVideoSidebar, $content);

    // We only want to replace oldVideoEnd with newVideoEnd if it's right after the Video Sidebar block.
    // Let's do it with preg_replace
    $content = preg_replace('/(\<div class="aspect-square bg-gray-100 .*?)\n(\s*\<\/div>\n\s*\<\/div>)/s', "$1\n$2\n                                @endif", $content, 1);

    file_put_contents($file, $content);
}

replaceMediaRendering('resources/views/groups/show.blade.php');
replaceMediaRendering('resources/views/pages/show.blade.php');
replaceMediaRendering('resources/views/groups/media.blade.php');
replaceMediaRendering('resources/views/pages/media.blade.php');
