<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiSecurityLog;
use App\Models\BlockedIp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AiSecurityWebhookController extends Controller
{
    /**
     * Terbitkan token Sanctum bagi Agen AI / Admin untuk autentikasi ke endpoint webhook.
     * Endpoint: POST /api/ai-security/token
     */
    public function issueToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username'    => 'required|string',
            'password'    => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        $user = User::where('username', $validated['username'])
            ->orWhere('email', $validated['username'])
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kredensial tidak valid.',
            ], 401);
        }

        if (!$user->isAdmin()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya akun Administrator yang diizinkan membuat token AI Security Webhook.',
            ], 403);
        }

        $tokenName = $validated['device_name'] ?? 'crewai-security-agent';
        $token = $user->createToken($tokenName, ['ai-security:write']);

        return response()->json([
            'status'     => 'success',
            'message'    => 'Token Sanctum berhasil dibuat.',
            'token'      => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Terima payload webhook dari Agen AI (CrewAI / Python) dan simpan ke tabel ai_security_logs
     * tanpa melakukan broadcast publik ke beranda (Admin-Only Architecture).
     *
     * Endpoint: POST /api/ai-security-logs (dan alias POST /api/webhook/ai-security)
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user && method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Forbidden: Token tidak memiliki hak akses administrator.',
            ], 403);
        }

        if ($user && method_exists($request->user(), 'tokenCan')) {
            if (!$request->user()->tokenCan('ai-security:write') && !$request->user()->tokenCan('*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Forbidden: Token tidak memiliki izin ai-security:write.',
                ], 403);
            }
        }

        $payload = $request->all();

        // Dukung pengiriman single object maupun batch array ({"logs": [...]} atau [...])
        $items = [];
        if (isset($payload['logs']) && is_array($payload['logs'])) {
            $items = $payload['logs'];
        } elseif (array_is_list($payload) && !empty($payload) && is_array($payload[0] ?? null)) {
            $items = $payload;
        } else {
            $items = [$payload];
        }

        if (empty($items)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Payload kosong.',
            ], 422);
        }

        $createdLogs = [];
        $errors = [];

        foreach ($items as $index => $rawItem) {
            if (!is_array($rawItem)) {
                $errors[$index] = ['Format item tidak valid.'];
                continue;
            }

            $normalized = $this->normalizePayloadItem($rawItem, $request->ip());

            $validator = Validator::make($normalized, [
                'detected_at' => 'nullable|date',
                'attack_type' => 'required|string|max:150',
                'source_ip'   => 'nullable|string|max:64',
                'target'      => 'required|string|max:500',
                'severity'    => 'required|string|in:critical,high,medium,low,info',
                'ai_analysis' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors[$index] = $validator->errors()->toArray();
                continue;
            }

            $sourceIp = $normalized['source_ip'];
            $isAlreadyBlocked = $sourceIp ? BlockedIp::isBlocked($sourceIp) : false;

            $log = AiSecurityLog::create([
                'detected_at' => $normalized['detected_at'] ? Carbon::parse($normalized['detected_at']) : now(),
                'attack_type' => $normalized['attack_type'],
                'source_ip'   => $sourceIp,
                'target'      => $normalized['target'],
                'severity'    => $normalized['severity'],
                'ai_analysis' => $normalized['ai_analysis'],
                'status'      => $isAlreadyBlocked ? 'mitigated' : 'open',
                'ip_blocked'  => $isAlreadyBlocked,
                'raw_payload' => $rawItem,
            ]);

            $createdLogs[] = [
                'id'          => $log->id,
                'timestamp'   => $log->detected_at?->toIso8601String(),
                'attack_type' => $log->attack_type,
                'source_ip'   => $log->source_ip,
                'target'      => $log->target,
                'severity'    => $log->severity,
                'ai_analysis' => $log->ai_analysis,
                'status'      => $log->status,
                'ip_blocked'  => $log->ip_blocked,
            ];
        }

        if (empty($createdLogs) && !empty($errors)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi payload gagal.',
                'errors'  => count($items) === 1 ? ($errors[0] ?? $errors) : $errors,
            ], 422);
        }

        return response()->json([
            'status'        => 'success',
            'message'       => count($createdLogs) === 1
                ? 'Log keamanan AI berhasil dicatat.'
                : count($createdLogs) . ' log keamanan AI berhasil dicatat.',
            'recorded_count'=> count($createdLogs),
            'data'          => count($createdLogs) === 1 ? $createdLogs[0] : $createdLogs,
            'errors'        => !empty($errors) ? $errors : null,
        ], 201);
    }

    /**
     * Normalisasi variasi key JSON dari skrip Python / CrewAI:
     * Mendukung snake_case, Title Case (sesuai dokumen briefing), maupun camelCase.
     */
    private function normalizePayloadItem(array $item, ?string $fallbackIp = null): array
    {
        $rawTimestamp = $item['timestamp']
            ?? $item['Timestamp']
            ?? $item['detected_at']
            ?? $item['time']
            ?? null;

        $detectedAt = now()->toDateTimeString();
        if (!empty($rawTimestamp)) {
            try {
                if (is_numeric($rawTimestamp)) {
                    $detectedAt = Carbon::createFromTimestamp((int) $rawTimestamp)->toDateTimeString();
                } else {
                    $detectedAt = Carbon::parse((string) $rawTimestamp)->toDateTimeString();
                }
            } catch (\Throwable $e) {
                $detectedAt = now()->toDateTimeString();
            }
        }

        $attackType = $item['attack_type']
            ?? $item['Attack Type']
            ?? $item['attackType']
            ?? $item['type']
            ?? '';

        $sourceIp = $item['source_ip']
            ?? $item['Source IP']
            ?? $item['sourceIp']
            ?? $item['ip']
            ?? $fallbackIp;

        $target = $item['target']
            ?? $item['Target']
            ?? $item['url']
            ?? $item['endpoint']
            ?? '';

        $rawSeverity = $item['severity']
            ?? $item['Severity']
            ?? 'medium';

        $rawAnalysis = $item['ai_analysis']
            ?? $item['AI Analysis']
            ?? $item['aiAnalysis']
            ?? $item['analysis']
            ?? null;

        if (is_array($rawAnalysis) || is_object($rawAnalysis)) {
            $rawAnalysis = json_encode($rawAnalysis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return [
            'detected_at' => $detectedAt,
            'attack_type' => trim((string) $attackType),
            'source_ip'   => $sourceIp !== null ? trim((string) $sourceIp) : null,
            'target'      => trim((string) $target),
            'severity'    => AiSecurityLog::normalizeSeverity((string) $rawSeverity),
            'ai_analysis' => $rawAnalysis !== null ? trim((string) $rawAnalysis) : null,
        ];
    }
}
