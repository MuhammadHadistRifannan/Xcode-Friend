<?php

use App\Http\Controllers\Api\AiSecurityWebhookController;
use Illuminate\Support\Facades\Route;

// Endpoint untuk menukar kredensial Admin menjadi Bearer Token Sanctum (untuk skrip Python / CrewAI Tim B)
Route::post('/ai-security/token', [AiSecurityWebhookController::class, 'issueToken'])
    ->middleware('throttle:15,1')
    ->name('api.ai-security.token');

// Endpoint Privat (Laravel Sanctum) untuk menerima Webhook Log Keamanan dari Agen AI (CrewAI)
Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
    Route::post('/ai-security-logs', [AiSecurityWebhookController::class, 'store'])
        ->name('api.ai-security-logs.store');

    Route::post('/webhook/ai-security', [AiSecurityWebhookController::class, 'store'])
        ->name('api.webhook.ai-security');
});
