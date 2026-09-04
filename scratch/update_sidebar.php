<?php

$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/profile/dinding.blade.php';
$content = file_get_contents($file);

$searchSidebar = <<<'EOD'
            <!-- Mengikuti & Teman -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">MENGIKUTI</h4>
                    <a href="#" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No following yet</div>
            </div>

            <!-- Teman -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">TEMAN</h4>
                    <a href="{{ route('telusur.index') }}" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($profileUser->friends && $profileUser->friends->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profileUser->friends->take(6) as $friend)
                            <a href="{{ url('/profile/'.$friend->username) }}" class="block">
                                <img src="{{ $friend->avatar_url }}" alt="{{ $friend->fullname ?: $friend->username }}" class="w-10 h-10 rounded-full object-cover border border-neutral-200" title="{{ $friend->fullname ?: $friend->username }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No friends yet</div>
                @endif
            </div>
EOD;

$replaceSidebar = <<<'EOD'
            <!-- Pengikut -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">PENGIKUT</h4>
                    <a href="?tab=koneksi" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($profileUser->followerUsers && $profileUser->followerUsers->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profileUser->followerUsers->take(6) as $follower)
                            <a href="{{ url('/profile/'.$follower->username) }}" class="block">
                                <img src="{{ $follower->avatar_url }}" alt="{{ $follower->fullname ?: $follower->username }}" class="w-10 h-10 rounded-full object-cover border border-neutral-200" title="{{ $follower->fullname ?: $follower->username }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">Belum ada pengikut</div>
                @endif
            </div>

            <!-- Mengikuti -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">MENGIKUTI</h4>
                    <a href="?tab=koneksi" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($profileUser->followingUsers && $profileUser->followingUsers->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profileUser->followingUsers->take(6) as $following)
                            <a href="{{ url('/profile/'.$following->username) }}" class="block">
                                <img src="{{ $following->avatar_url }}" alt="{{ $following->fullname ?: $following->username }}" class="w-10 h-10 rounded-full object-cover border border-neutral-200" title="{{ $following->fullname ?: $following->username }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">Belum mengikuti siapapun</div>
                @endif
            </div>

            <!-- Teman -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">TEMAN</h4>
                    <a href="?tab=koneksi" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($profileUser->friends && $profileUser->friends->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profileUser->friends->take(6) as $friend)
                            <a href="{{ url('/profile/'.$friend->username) }}" class="block">
                                <img src="{{ $friend->avatar_url }}" alt="{{ $friend->fullname ?: $friend->username }}" class="w-10 h-10 rounded-full object-cover border border-neutral-200" title="{{ $friend->fullname ?: $friend->username }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">Belum ada teman</div>
                @endif
            </div>
EOD;

$content = str_replace($searchSidebar, $replaceSidebar, $content);
file_put_contents($file, $content);
echo "Sidebar widget updated!\n";
