<?php

$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/profile/dinding.blade.php';
$content = file_get_contents($file);

// 1. Remove the "Koneksi" tab and add "Teman", "Pengikut", "Mengikuti" in the horizontal menu
$searchTab = '<a href="?tab=koneksi" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === \'koneksi\' ? \'text-red-700 border-b-2 border-red-700\' : \'text-neutral-500 hover:text-neutral-800\' }}">Koneksi</a>';
$replaceTabs = <<<'EOD'
            <a href="?tab=teman" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'teman' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Teman</a>
            <a href="?tab=pengikut" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'pengikut' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Pengikut</a>
            <a href="?tab=mengikuti" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'mengikuti' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Mengikuti</a>
EOD;

$content = str_replace($searchTab, $replaceTabs, $content);

// 2. Change the tab contents (from @elseif($tab === 'koneksi') to three separate tabs)
$searchContent = <<<'EOD'
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
EOD;

$replaceSeparateTabs = <<<'EOD'
            @elseif($tab === 'teman')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-6">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-widest mb-6 border-b border-neutral-100 pb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Teman {{ $profileUser->fullname }} ({{ $profileUser->friends()->count() }})
                    </h4>
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
            @elseif($tab === 'pengikut')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-6">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-widest mb-6 border-b border-neutral-100 pb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Pengikut {{ $profileUser->fullname }} ({{ $profileUser->followerUsers()->count() }})
                    </h4>
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
            @elseif($tab === 'mengikuti')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-6">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-widest mb-6 border-b border-neutral-100 pb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                        Yang Diikuti {{ $profileUser->fullname }} ({{ $profileUser->followingUsers()->count() }})
                    </h4>
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
EOD;

$content = str_replace($searchContent, $replaceSeparateTabs, $content);

// 3. Update the links in the sidebar
$content = str_replace('?tab=koneksi', '?tab=pengikut', $content); // We'll fix this below using regex because it changed all of them

// Let's do it properly
$patternSidebarPengikut = '/<h4 class="text-\[11px\] font-bold text-neutral-800 uppercase tracking-wider">PENGIKUT<\/h4>\s*<a href="\?tab=pengikut" class="text-\[9px\] font-bold text-red-700 hover:underline">Lihat Semua<\/a>/';
// Actually wait, let's just use str_replace on specific blocks.
// Better yet, just reload file contents and replace carefully.
file_put_contents($file, $content);
echo "Tabs separated.\n";
