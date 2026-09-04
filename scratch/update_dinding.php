<?php

$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/profile/dinding.blade.php';
$content = file_get_contents($file);

// 1. Ganti tampilan jumlah followers & following di sidebar profil teman
$search1 = <<<'EOD'
                    <p class="text-[10px] text-neutral-400 mb-4">
                        Terakhir dilihat: {{ $profileUser->last_active ? \Carbon\Carbon::createFromTimestamp($profileUser->last_active)->diffForHumans() : 'Baru saja' }}
                        <br>
                        <span class="font-semibold text-neutral-600">{{ $profileUser->followers ?? 1 }}</span> Followers
                    </p>
EOD;

$replace1 = <<<'EOD'
                    <p class="text-[10px] text-neutral-400 mb-3 text-center">
                        Terakhir dilihat: {{ $profileUser->last_active ? \Carbon\Carbon::createFromTimestamp($profileUser->last_active)->diffForHumans() : 'Baru saja' }}
                    </p>
                    <div class="flex items-center justify-center space-x-6 mb-5 border-y border-neutral-100 py-3">
                        <div class="text-center">
                            <span class="block text-sm font-extrabold text-neutral-800">{{ $profileUser->followerUsers()->count() }}</span>
                            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Pengikut</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-sm font-extrabold text-neutral-800">{{ $profileUser->followingUsers()->count() }}</span>
                            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Mengikuti</span>
                        </div>
                    </div>
EOD;

$content = str_replace($search1, $replace1, $content);

// 2. Ganti tampilan jumlah followers & following di sidebar profil sendiri
$search2 = <<<'EOD'
                    <p class="text-[10px] text-neutral-400 mb-4">
                        Terakhir dilihat: {{ $profileUser->last_active ? \Carbon\Carbon::createFromTimestamp($profileUser->last_active)->diffForHumans() : 'Baru saja' }}
                        <br>
                        <span class="font-semibold text-neutral-600">{{ $profileUser->followers ?? 1 }}</span> Followers
                    </p>
EOD;

// Because it appears twice, we might have already replaced both if we use str_replace on a non-unique block!
// Wait, the block is identical in both places. So $search1 replacement will replace BOTH! This is perfect!
// Let's verify if they are identical. They are identical except indentation maybe.
// Let's do a regex to match both.

$pattern = '/<p class="text-\[10px\] text-neutral-400 mb-4">\s*Terakhir dilihat: \{\{ \$profileUser->last_active \? \\\\Carbon\\\\Carbon::createFromTimestamp\(\$profileUser->last_active\)->diffForHumans\(\) : \'Baru saja\' \}\}\s*<br>\s*<span class="font-semibold text-neutral-600">\{\{ \$profileUser->followers \?\? 1 \}\}<\/span> Followers\s*<\/p>/s';

$content = preg_replace($pattern, $replace1, $content);

// 3. Tambahkan tab Koneksi di header navbar
$searchTab = '<a href="?tab=video" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === \'video\' ? \'text-red-700 border-b-2 border-red-700\' : \'text-neutral-500 hover:text-neutral-800\' }}">Video</a>';
$replaceTab = $searchTab . "\n" . '            <a href="?tab=koneksi" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === \'koneksi\' ? \'text-red-700 border-b-2 border-red-700\' : \'text-neutral-500 hover:text-neutral-800\' }}">Koneksi</a>';
$content = str_replace($searchTab, $replaceTab, $content);

// 4. Tambahkan isi tab Koneksi
$searchContent = '@elseif($tab === \'menyukai\')';
$replaceContent = <<<'EOD'
            @elseif($tab === 'koneksi')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-6">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-widest mb-6 border-b border-neutral-100 pb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Koneksi {{ $profileUser->fullname }}
                    </h4>
                    
                    <div class="space-y-8">
                        <!-- Teman -->
                        <div>
                            <h5 class="text-xs font-bold text-neutral-500 uppercase tracking-widest mb-4">Teman ({{ $profileUser->friends()->count() }})</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($profileUser->friends as $friend)
                                    <div class="flex items-center space-x-4 p-3 rounded-xl border border-neutral-100 hover:border-red-100 hover:shadow-sm bg-neutral-50 transition group">
                                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                                            <img src="{{ $friend->avatar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $friend->username) }}" class="text-sm font-bold text-neutral-800 hover:text-red-700 block">{{ $friend->fullname }}</a>
                                            <span class="text-[10px] text-neutral-500">{{ '@' . $friend->username }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if($profileUser->friends->isEmpty())
                                    <p class="text-xs text-neutral-400 col-span-1 md:col-span-2 bg-neutral-50 py-4 text-center rounded-lg border border-dashed border-neutral-200">Belum ada teman.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Pengikut -->
                        <div>
                            <h5 class="text-xs font-bold text-neutral-500 uppercase tracking-widest mb-4">Pengikut ({{ $profileUser->followerUsers()->count() }})</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($profileUser->followerUsers as $follower)
                                    <div class="flex items-center space-x-4 p-3 rounded-xl border border-neutral-100 hover:border-red-100 hover:shadow-sm bg-neutral-50 transition group">
                                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                                            <img src="{{ $follower->avatar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $follower->username) }}" class="text-sm font-bold text-neutral-800 hover:text-red-700 block">{{ $follower->fullname }}</a>
                                            <span class="text-[10px] text-neutral-500">{{ '@' . $follower->username }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if($profileUser->followerUsers->isEmpty())
                                    <p class="text-xs text-neutral-400 col-span-1 md:col-span-2 bg-neutral-50 py-4 text-center rounded-lg border border-dashed border-neutral-200">Belum ada pengikut.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Mengikuti -->
                        <div>
                            <h5 class="text-xs font-bold text-neutral-500 uppercase tracking-widest mb-4">Mengikuti ({{ $profileUser->followingUsers()->count() }})</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($profileUser->followingUsers as $following)
                                    <div class="flex items-center space-x-4 p-3 rounded-xl border border-neutral-100 hover:border-red-100 hover:shadow-sm bg-neutral-50 transition group">
                                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                                            <img src="{{ $following->avatar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $following->username) }}" class="text-sm font-bold text-neutral-800 hover:text-red-700 block">{{ $following->fullname }}</a>
                                            <span class="text-[10px] text-neutral-500">{{ '@' . $following->username }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if($profileUser->followingUsers->isEmpty())
                                    <p class="text-xs text-neutral-400 col-span-1 md:col-span-2 bg-neutral-50 py-4 text-center rounded-lg border border-dashed border-neutral-200">Belum mengikuti siapapun.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tab === 'menyukai')
EOD;

$content = str_replace($searchContent, $replaceContent, $content);

file_put_contents($file, $content);
echo "Berhasil update dinding.blade.php\n";
