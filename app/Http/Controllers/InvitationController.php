<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Invite;

class InvitationController extends Controller
{
    /**
     * Menampilkan halaman Invitation dengan sistem Tab.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buat atau ambil kode referral unik untuk user yang sedang login.
        // Karena tabel jcow_invites tidak punya kolom code, 
        // kita menggunakan username user sebagai kode unik undangan.
        $inviteCode = $user->username ?? 'user' . $user->id;
        
        // Buat URL undangan
        $inviteUrl = url('/register?ref=' . urlencode($inviteCode));
        
        // Ambil data "Sejarah" undangan
        $histories = Invite::where('uid', $user->id)
            ->orderBy('created', 'desc')
            ->get();
            
        return view('invitations.index', compact('inviteUrl', 'histories'));
    }

    /**
     * Proses pengiriman email undangan.
     */
    public function sendEmail(Request $request)
    {
        // Validasi input email
        $request->validate([
            'emails' => 'required|string',
        ]);
        
        $emailsInput = $request->input('emails');
        
        // Pisahkan email berdasarkan koma, lalu bersihkan spasi
        $emailArray = array_filter(array_map('trim', explode(',', $emailsInput)));
        
        // Batasi maksimal 5 email sekaligus
        if (count($emailArray) > 5) {
            return back()->with('error', 'Maksimal 5 email dalam satu pengiriman.');
        }
        
        $user = Auth::user();
        $inviteCode = $user->username ?? 'user' . $user->id;
        $inviteUrl = url('/register?ref=' . urlencode($inviteCode));
        
        $sentCount = 0;
        
        foreach ($emailArray as $email) {
            // Validasi format tiap email
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                
                // Gunakan Mail::raw atau Mail::send (di sini pakai raw untuk kesederhanaan)
                // Pastikan Mailer dikonfigurasi di .env
                try {
                    Mail::raw("Halo! Anda telah diundang oleh {$user->username} untuk bergabung. Klik link berikut untuk mendaftar: {$inviteUrl}", function ($message) use ($email) {
                        $message->to($email)
                                ->subject('Undangan Bergabung ke Platform');
                    });
                    
                    // Simpan riwayat pengiriman ke database (jcow_invites)
                    Invite::create([
                        'uid'     => $user->id,
                        'email'   => $email,
                        'status'  => 0, // 0 = Pending/Terkirim
                        'created' => time(),
                    ]);
                    
                    $sentCount++;
                } catch (\Exception $e) {
                    // Abaikan jika gagal mengirim satu email tertentu
                }
            }
        }
        
        if ($sentCount > 0) {
            return back()->with('success', "Berhasil mengirim {$sentCount} undangan email!");
        } else {
            return back()->with('error', 'Gagal mengirim undangan. Pastikan format email sudah benar dan pengaturan SMTP valid.');
        }
    }
}
