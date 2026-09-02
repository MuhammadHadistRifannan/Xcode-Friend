<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%");
        }

        $members = $query->orderBy('created', 'desc')->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($id);
        
        // Asumsi kolom roles menampung string seperti 'admin', 'member'
        // Jika menggunakan kolom 'level' (tinyint), kita bisa menyesuaikan logikanya di sini
        $user->roles = $request->roles;
        
        // Optional: jika admin role, kita set level juga misal level 1
        if (strtolower($request->roles) === 'admin' || strtolower($request->roles) === 'administrator') {
            $user->level = 1;
        } else {
            $user->level = 0;
        }

        $user->save();

        return back()->with('success', "Role untuk user {$user->username} berhasil diperbarui.");
    }

    public function banMember($id)
    {
        $user = User::findOrFail($id);
        
        // Toggle disable/ban
        $user->disabled = $user->disabled ? 0 : 1;
        $user->save();

        $status = $user->disabled ? 'diblokir' : 'diaktifkan kembali';
        return back()->with('success', "User {$user->username} berhasil {$status}.");
    }
}
