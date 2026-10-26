<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stream;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, $username)
    {
        // Cari user berdasarkan username di URL
        $profileUser = User::where('username', $username)->firstOrFail();
        
        $viewer = auth()->user();
        $isMe = $viewer && (int) $viewer->id === (int) $profileUser->id;
        $blockedByMe = false;
        $blockedMe = false;

        if ($viewer && !$isMe) {
            $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
            $authId = (int) $viewer->id;
            $blockedByMe = $friendRepo->hasBlocked($authId, (int) $profileUser->id);
            $blockedMe = $friendRepo->isBlockedBy($authId, (int) $profileUser->id);
        }

        // Jika profileUser memblokir viewer saat ini, akses profil diblokir sepenuhnya
        if ($blockedMe) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Profil tidak tersedia.'], 403);
            }
            return view('profile.blocked', compact('profileUser'));
        }

        $tab = $request->query('tab', 'dinding');
        $streams = null;
        $photos = null;
        $videos = null;

        $isFollowing = false;
        $isFriend = false;
        $hasPendingRequest = false;
        $hasSentRequest = false;
        $isBlocked = $blockedByMe;

        // Jika viewer memblokir profileUser, tidak perlu mengambil data dinding/foto/video
        if (!$blockedByMe) {
            if ($viewer && !$isMe) {
                $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
                $authId = (int) $viewer->id;
                $isFollowing = $friendRepo->isFollowing($authId, $profileUser->id);
                $isFriend = $friendRepo->areFriends($authId, $profileUser->id);
                $hasPendingRequest = $friendRepo->hasPendingRequest($authId, $profileUser->id);
                $hasSentRequest = $friendRepo->hasPendingRequest($profileUser->id, $authId);
            }

            if ($tab === 'dinding') {
                // Ambil postingan (feed) milik user profil ini saja
                $streams = Stream::with(['user', 'comments.user', 'targetPage'])
                            ->where('uid', $profileUser->id)
                            ->where('hide', 0)
                            ->visibleTo(auth()->user())
                            ->orderBy('created', 'desc')
                            ->paginate(10);
            } elseif ($tab === 'menyukai') {
                // Ambil postingan yang di-like oleh user ini
                $streams = Stream::with(['user', 'comments.user', 'targetPage'])
                            ->whereHas('likedBy', function($q) use ($profileUser) {
                                $q->where('uid', $profileUser->id);
                            })
                            ->where('hide', 0)
                            ->visibleTo(auth()->user())
                            ->orderBy('created', 'desc')
                            ->paginate(10);
            } elseif ($tab === 'foto') {
                // Sinkronisasikan foto album milik pengguna yang belum memiliki record di jcow_streams
                $userAlbumIds = \App\Models\Album::where('gid', $profileUser->id)->pluck('id');
                if ($userAlbumIds->isNotEmpty()) {
                    $unsyncedPhotos = \App\Models\Photo::whereIn('sid', $userAlbumIds)->get();
                    foreach ($unsyncedPhotos as $pItem) {
                        if (!$pItem->fileExists()) {
                            $pItem->delete();
                            continue;
                        }
                        $hasStream = Stream::where('uid', $profileUser->id)
                            ->where(function($q) use ($pItem) {
                                $q->where('attachment', 'like', '%' . $pItem->uri . '%')
                                  ->orWhere('attachment', 'like', '%' . basename($pItem->uri) . '%')
                                  ->orWhere(function($q2) use ($pItem) {
                                      $q2->where('aid', $pItem->id)->whereIn('app', ['photo', 'photos']);
                                  });
                            })->exists();

                        if (!$hasStream) {
                            $album = \App\Models\Album::find($pItem->sid);
                            Stream::create([
                                'message'    => $pItem->des ?: ('Mengunggah foto ke album ' . ($album ? $album->name : 'Foto')),
                                'uid'        => $profileUser->id,
                                'created'    => time(),
                                'type'       => 2,
                                'app'        => 'photos',
                                'wall_id'    => 0,
                                'attachment' => json_encode([
                                    'photos'   => [$pItem->uri],
                                    'album_id' => $pItem->sid,
                                ]),
                                'aid'        => $pItem->sid,
                                'hide'       => 0,
                                'likes'      => 0,
                                'privacy'    => 'public',
                            ]);
                        }
                    }
                }

                $photos = Stream::where('uid', $profileUser->id)
                            ->where('attachment', '!=', '')
                            ->where('hide', 0)
                            ->visibleTo(auth()->user())
                            ->where(function($q) {
                                $q->where('type', 2)
                                  ->orWhereIn('app', ['photo', 'photos'])
                                  ->orWhere(function($q2) {
                                      $q2->whereIn('app', ['group', 'page'])
                                         ->where('attachment', 'not like', 'youtube:%');
                                  });
                            })
                            ->orderBy('created', 'desc')
                            ->paginate(12);
            } elseif ($tab === 'video') {
                // Sinkronisasikan video milik pengguna di jcow_stories yang belum ada di jcow_streams
                $unsyncedVideos = \App\Models\Video::where('uid', $profileUser->id)->where('app', 'video')->get();
                foreach ($unsyncedVideos as $vItem) {
                    $hasStream = Stream::where('uid', $profileUser->id)
                        ->where(function($q) use ($vItem) {
                            $q->where('attachment', 'like', '%' . $vItem->var1 . '%')
                              ->orWhere('aid', $vItem->id);
                        })->exists();

                    if (!$hasStream) {
                        Stream::create([
                            'message'    => $vItem->title,
                            'uid'        => $profileUser->id,
                            'created'    => $vItem->created ?: time(),
                            'type'       => 3,
                            'app'        => 'video',
                            'wall_id'    => 0,
                            'attachment' => json_encode([
                                'video_url' => $vItem->var1,
                                'album_id'  => $vItem->cid,
                                'title'     => $vItem->title,
                                'desc'      => $vItem->content ?? '',
                            ]),
                            'aid'        => $vItem->cid ?: 0,
                            'hide'       => 0,
                            'likes'      => 0,
                            'privacy'    => 'public',
                        ]);
                    }
                }

                $videos = Stream::where('uid', $profileUser->id)
                            ->where('attachment', '!=', '')
                            ->visibleTo(auth()->user())
                            ->where(function($q) {
                                $q->where('type', 3)
                                  ->orWhere(function($q2) {
                                      $q2->whereIn('app', ['group', 'page'])
                                         ->where('attachment', 'like', 'youtube:%');
                                  });
                            })
                            ->orderBy('created', 'desc')
                            ->paginate(12);
            }
        }

        $profileRaw = $profileUser->profile;
        $theme = $profileRaw ? json_decode($profileRaw->custom_css ?? '{}', true) : [];
        $musicplayer = $theme['musicplayer'] ?? false;
        $likedMusics = collect();

        if ($musicplayer && !$blockedByMe) {
            $likedMusics = Stream::where('app', 'music')
                ->whereHas('likedBy', function($q) use ($profileUser) {
                    $q->where('uid', $profileUser->id);
                })
                ->orderBy('created', 'desc')
                ->limit(10)
                ->get();
        }

        // Ambil Custom Fields yang aktif
        $customFields = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('jcow_profile_fields')) {
            $customFields = \Illuminate\Support\Facades\DB::table('jcow_profile_fields')->get();
        }

        if ($request->ajax()) {
            if ($blockedByMe) {
                return response()->json([
                    'html' => '',
                    'hasMorePages' => false,
                    'nextPageUrl' => null,
                ]);
            }
            if (($tab === 'dinding' || $tab === 'menyukai') && $streams) {
                $html = '';
                foreach ($streams as $stream) {
                    $html .= view('components.single-stream', compact('stream'))->render();
                }
                return response()->json([
                    'html' => $html,
                    'hasMorePages' => $streams->hasMorePages(),
                    'nextPageUrl' => $streams->nextPageUrl(),
                ]);
            }
        }

        return view('profile.dinding', compact(
            'profileUser', 'streams', 'photos', 'videos', 'tab',
            'isFollowing', 'isFriend', 'hasPendingRequest', 'hasSentRequest', 'isBlocked', 'blockedByMe', 'likedMusics', 'customFields'
        ));
    }

    public function updateBackground(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = auth()->user();
        
        if ($request->hasFile('background')) {
            $file = $request->file('background');
            // Format nama: BG-username-timestamp.ext
            $filename = 'BG-' . $user->username . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/backgrounds
            $file->storeAs('backgrounds', $filename, 'public');

            // Update atau Create data profil menggunakan Eloquent
            $profile = \App\Models\Profile::firstOrCreate(
                ['id' => $user->id],
                [
                    'style_ids' => '',
                    'custom_css' => '',
                    'background' => '',
                    'videoid' => 0,
                    'favorites' => 0,
                    'views' => 0
                ]
            );
            
            $profile->background = $filename;
            $profile->save();
        }

        return back()->with('success', 'Cover background berhasil diperbarui!');
    }

    public function updateDesign(Request $request)
    {
        $user = auth()->user();
        
        // Ambil pengaturan saat ini atau inisialisasi array kosong
        $settings = json_decode($user->settings, true) ?: [];
        
        // Simpan 4 preferensi (centang = 1, tidak = 0)
        $settings['music'] = $request->has('music') ? 1 : 0;
        $settings['wallpaper'] = $request->has('wallpaper') ? 1 : 0;
        $settings['public_page'] = $request->has('public_page') ? 1 : 0;
        $settings['block_title'] = $request->has('block_title') ? 1 : 0;
        
        // Update field settings di tabel jcow_accounts
        $user->settings = json_encode($settings);
        $user->save();

        return back()->with('success', 'Pengaturan tampilan berhasil diperbarui.');
    }


    public function edit(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'informasi');
        
        $customFields = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('jcow_profile_fields')) {
            $customFields = \Illuminate\Support\Facades\DB::table('jcow_profile_fields')->get();
        }

        $blockedUsers = collect();
        if ($tab === 'blokir') {
            $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
            $blockedUsers = $friendRepo->getBlockedUsers($user->id);
        }

        return view('profile.edit', compact('user', 'customFields', 'blockedUsers', 'tab'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $type = $request->input('type');

        if ($type === 'informasi') {
            $request->validate([
                'fullname' => 'required|string|max:30',
                'email' => 'required|email|max:120',
                'gender' => 'required|in:1,2,0',
                'about_me' => 'nullable|string|max:1000',
                'location' => 'nullable|string|max:100',
                'birthyear' => 'nullable|integer',
                'birthmonth' => 'nullable|integer|between:1,12',
                'birthday' => 'nullable|integer|between:1,31',
            ]);

            $user->fullname = $request->fullname;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->about_me = $request->about_me;
            $user->location = $request->location;
            
            if ($request->filled('birthyear')) $user->birthyear = $request->birthyear;
            if ($request->filled('birthmonth')) $user->birthmonth = $request->birthmonth;
            if ($request->filled('birthday')) $user->birthday = $request->birthday;
            
            // Simpan custom fields (var1 - var7)
            if (\Illuminate\Support\Facades\Schema::hasTable('jcow_profile_fields')) {
                $customFields = \Illuminate\Support\Facades\DB::table('jcow_profile_fields')->get();
                foreach ($customFields as $index => $field) {
                    if ($field->type !== 'Disabled') {
                        $col = 'var' . ($index + 1);
                        if ($request->has($col)) {
                            $user->{$col} = $request->input($col);
                        }
                    }
                }
            }

            $user->save();
            return redirect()->route('profile.edit', ['tab' => 'informasi'])->with('success', 'Informasi dasar berhasil diperbarui!');
        } 
        
        elseif ($type === 'avatar') {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            ]);
            
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'AV-' . $user->username . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('avatars', $filename, 'public');
                
                $user->avatar = $filename;
                $user->save();
            }
            return redirect()->route('profile.edit', ['tab' => 'gambar'])->with('success', 'Gambar pengenal berhasil diperbarui!');
        } 
        
        elseif ($type === 'pemberitahuan') {
            // Retrieve settings as JSON array
            $settings = json_decode($user->settings, true) ?? [];
            
            $settings['notif_pesan'] = $request->has('notif_pesan');
            $settings['notif_req_teman'] = $request->has('notif_req_teman');
            $settings['notif_acc_teman'] = $request->has('notif_acc_teman');
            $settings['notif_dinding'] = $request->has('notif_dinding');
            $settings['notif_komentar'] = $request->has('notif_komentar');
            $settings['notif_grup'] = $request->has('notif_grup');
            
            $user->settings = json_encode($settings);
            $user->save();
            
            return redirect()->route('profile.edit', ['tab' => 'pemberitahuan'])->with('success', 'Pengaturan pemberitahuan berhasil disimpan!');
        } 
        
        elseif ($type === 'privasi') {
            $request->validate([
                'profile_permission' => 'required|in:0,1,2',
            ]);
            
            $user->profile_permission = $request->profile_permission;
            $user->hide_me = $request->has('hide_search') ? 1 : 0;
            
            // "hide_likes" logic can be stored in var1 or similar if needed, or we just let it be. 
            // We'll use settings for hide_likes
            $settings = json_decode($user->settings, true) ?? [];
            $settings['hide_likes'] = $request->hide_likes == 1; // 1 means Tidak / hide
            $user->settings = json_encode($settings);
            
            $user->save();
            return redirect()->route('profile.edit', ['tab' => 'privasi'])->with('success', 'Pengaturan privasi berhasil diperbarui!');
        } 
        
        elseif ($type === 'sandi') {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
            
            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Sandi saat ini tidak sesuai.']);
            }
            
            $user->password = \Hash::make($request->password);
            $user->save();
            return redirect()->route('profile.edit', ['tab' => 'sandi'])->with('success', 'Kata sandi berhasil diubah!');
        }


        return back();
    }
}