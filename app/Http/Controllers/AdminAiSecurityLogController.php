<?php

namespace App\Http\Controllers;

use App\Models\AiSecurityLog;
use App\Models\BlockedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAiSecurityLogController extends Controller
{
    /**
     * Tampilkan halaman khusus Panel Admin (/admin/ai-security-logs).
     */
    public function index(Request $request)
    {
        $query = AiSecurityLog::query();

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('attack_type', 'like', "%{$search}%")
                  ->orWhere('source_ip', 'like', "%{$search}%")
                  ->orWhere('target', 'like', "%{$search}%")
                  ->orWhere('ai_analysis', 'like', "%{$search}%");
            });
        }

        if ($severity = $request->input('severity')) {
            if ($severity !== 'all') {
                $query->where('severity', strtolower($severity));
            }
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', strtolower($status));
            }
        }

        $logs = $query->orderByDesc('detected_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'         => AiSecurityLog::count(),
            'critical_high' => AiSecurityLog::whereIn('severity', ['critical', 'high'])->count(),
            'today'         => AiSecurityLog::where('created_at', '>=', now()->startOfDay())->count(),
            'blocked_ips'   => Schema::hasTable('blocked_ips') ? BlockedIp::count() : 0,
            'critical'      => AiSecurityLog::where('severity', 'critical')->count(),
            'high'          => AiSecurityLog::where('severity', 'high')->count(),
            'medium'        => AiSecurityLog::where('severity', 'medium')->count(),
            'low'           => AiSecurityLog::whereIn('severity', ['low', 'info'])->count(),
        ];

        $blockedIps = Schema::hasTable('blocked_ips')
            ? BlockedIp::orderByDesc('created_at')->take(50)->get()
            : collect();

        $tokens = collect();
        if (Schema::hasTable('personal_access_tokens') && Auth::user()) {
            $tokens = Auth::user()->tokens()->orderByDesc('created_at')->get();
        }

        return view('admin.ai-security-logs.index', compact('logs', 'stats', 'blockedIps', 'tokens'));
    }

    /**
     * Blokir alamat IP sumber serangan (IP Blocking) dari panel admin.
     */
    public function blockIp(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|string|max:64',
            'reason'     => 'nullable|string|max:255',
            'log_id'     => 'nullable|integer',
        ]);

        $ip = trim($validated['ip_address']);
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return back()->with('error', 'Alamat IP localhost tidak dapat diblokir.');
        }

        BlockedIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason'             => $validated['reason'] ?? 'Diblokir melalui AI Security Log Panel',
                'ai_security_log_id' => $validated['log_id'] ?? null,
                'blocked_by'         => Auth::id(),
                'expires_at'         => null,
            ]
        );

        AiSecurityLog::where('source_ip', $ip)->update([
            'ip_blocked' => true,
            'status'     => 'mitigated',
        ]);

        // Sinkronisasi ke tabel legacy jcow_banned jika IPv4 valid
        if (Schema::hasTable('jcow_banned') && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                DB::table('jcow_banned')->updateOrInsert(
                    ['ip1' => $parts[0], 'ip2' => $parts[1], 'ip3' => $parts[2], 'ip4' => $parts[3]],
                    [
                        'username' => '',
                        'created'  => time(),
                        'expired'  => time() + (365 * 86400),
                        'operator' => Auth::user()->username ?? 'admin',
                    ]
                );
            }
        }

        BlockedIp::clearBlockedCache();

        return back()->with('success', "Alamat IP {$ip} berhasil diblokir dan status insiden ditandai Mitigated.");
    }

    /**
     * Buka blokir alamat IP.
     */
    public function unblockIp(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|string|max:64',
        ]);

        $ip = trim($validated['ip_address']);

        BlockedIp::where('ip_address', $ip)->delete();
        AiSecurityLog::where('source_ip', $ip)->update([
            'ip_blocked' => false,
        ]);

        if (Schema::hasTable('jcow_banned') && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                DB::table('jcow_banned')
                    ->where('ip1', $parts[0])
                    ->where('ip2', $parts[1])
                    ->where('ip3', $parts[2])
                    ->where('ip4', $parts[3])
                    ->delete();
            }
        }

        BlockedIp::clearBlockedCache();

        return back()->with('success', "Blokir pada alamat IP {$ip} berhasil dibuka.");
    }

    /**
     * Perbarui status penanganan log keamanan (open, investigating, mitigated, false_positive).
     */
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,investigating,mitigated,false_positive,ignored',
        ]);

        $log = AiSecurityLog::findOrFail($id);
        $log->update(['status' => $validated['status']]);

        return back()->with('success', 'Status temuan keamanan berhasil diperbarui.');
    }

    /**
     * Buat token Sanctum baru untuk integrasi Webhook Agen AI (Tim B).
     */
    public function generateToken(Request $request)
    {
        $validated = $request->validate([
            'token_name' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $name = trim($validated['token_name'] ?? '') ?: 'CrewAI-Security-Webhook';
        $token = $user->createToken($name, ['ai-security:write']);

        return back()
            ->with('success', 'Token Sanctum untuk Webhook AI berhasil dibuat!')
            ->with('new_sanctum_token', $token->plainTextToken);
    }

    /**
     * Cabut / hapus token Sanctum.
     */
    public function revokeToken(int $tokenId)
    {
        $user = Auth::user();
        $user->tokens()->where('id', $tokenId)->delete();

        return back()->with('success', 'Token API Sanctum berhasil dicabut.');
    }

    /**
     * Simulasi pengiriman payload log keamanan untuk uji coba integrasi di panel admin.
     */
    public function simulateWebhook(Request $request)
    {
        $samples = [
            [
                'attack_type' => 'SQL Injection (UNION-Based)',
                'source_ip'   => '203.0.113.42',
                'target'      => '/search?q=\' UNION SELECT username,password FROM jcow_accounts--',
                'severity'    => 'critical',
                'ai_analysis' => "Agen AI (CrewAI) mendeteksi pola payload UNION SELECT pada parameter 'q' di endpoint /search. Percobaan ini bertujuan mengekstrak kredensial tabel jcow_accounts.\n\nRekomendasi Mitigasi:\n1. Pastikan query menggunakan Eloquent / Prepared Statements (terkonfirmasi aman).\n2. Blokir IP sumber 203.0.113.42 karena melakukan enumerasi berulang (18 request/menit).",
            ],
            [
                'attack_type' => 'Cross-Site Scripting (Reflected XSS)',
                'source_ip'   => '198.51.100.17',
                'target'      => '/stream (POST parameter: message)',
                'severity'    => 'high',
                'ai_analysis' => "Terdeteksi injeksi tag <script> dan atribut event handler onerror= pada pengiriman stream baru. Output Blade {{ }} telah melakukan auto-escaping, namun disarankan memantau IP 198.51.100.17.",
            ],
            [
                'attack_type' => 'Brute Force Authentication',
                'source_ip'   => '192.0.2.88',
                'target'      => '/login',
                'severity'    => 'high',
                'ai_analysis' => "Agen AI mengidentifikasi 35 percobaan login gagal berturut-turut terhadap akun administrator dalam kurun waktu 90 detik dari IP 192.0.2.88. Disarankan segera melakukan IP Blocking.",
            ],
        ];

        $sample = $samples[array_rand($samples)];
        $isBlocked = BlockedIp::isBlocked($sample['source_ip']);

        AiSecurityLog::create([
            'detected_at' => now(),
            'attack_type' => $sample['attack_type'],
            'source_ip'   => $sample['source_ip'],
            'target'      => $sample['target'],
            'severity'    => $sample['severity'],
            'ai_analysis' => $sample['ai_analysis'],
            'status'      => $isBlocked ? 'mitigated' : 'open',
            'ip_blocked'  => $isBlocked,
            'raw_payload' => array_merge(['timestamp' => now()->toIso8601String()], $sample, ['simulated' => true]),
        ]);

        return back()->with('success', 'Simulasi payload AI Security Log berhasil dicatat.');
    }

    /**
     * Hapus satu baris log keamanan AI.
     */
    public function destroy(int $id)
    {
        AiSecurityLog::where('id', $id)->delete();

        return back()->with('success', 'Log keamanan AI berhasil dihapus.');
    }

    /**
     * Bersihkan seluruh riwayat log keamanan AI.
     */
    public function clear()
    {
        AiSecurityLog::truncate();

        return back()->with('success', 'Seluruh riwayat AI Security Log berhasil dibersihkan.');
    }
}
