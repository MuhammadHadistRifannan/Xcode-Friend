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
        
        $tab = $request->query('tab', 'dinding');
        $streams = null;
        $photos = null;
        $videos = null;

        if ($tab === 'dinding') {
            // Ambil postingan (feed) milik user ini saja
            $streams = Stream::with(['user', 'comments.user'])
                        ->where('uid', $profileUser->id)
                        ->orderBy('created', 'desc')
                        ->paginate(10);
        } elseif ($tab === 'menyukai') {
            // Ambil postingan yang di-like oleh user ini
            $streams = Stream::with(['user', 'comments.user'])
                        ->whereHas('likedBy', function($q) use ($profileUser) {
                            $q->where('uid', $profileUser->id);
                        })
                        ->orderBy('created', 'desc')
                        ->paginate(10);
        } elseif ($tab === 'foto') {
            // Asumsi JCow menggunakan jcow_stories dengan app='photos'
            $photos = \App\Models\Story::where('uid', $profileUser->id)
                        ->where('app', 'photos')
                        ->orderBy('created', 'desc')
                        ->paginate(12);
        } elseif ($tab === 'video') {
            // Asumsi JCow menggunakan jcow_stories dengan app='video'
            $videos = \App\Models\Story::where('uid', $profileUser->id)
                        ->whereIn('app', ['video', 'videos'])
                        ->orderBy('created', 'desc')
                        ->paginate(12);
        }

        return view('profile.dinding', compact('profileUser', 'streams', 'photos', 'videos', 'tab'));
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

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
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