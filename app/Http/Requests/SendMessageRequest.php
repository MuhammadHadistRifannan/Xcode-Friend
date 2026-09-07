<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_id' => 'required|integer|exists:jcow_accounts,id',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string',
            'reply_to' => 'nullable|integer|exists:jcow_messages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_id.required' => 'Penerima harus diisi.',
            'recipient_id.exists' => 'Penerima tidak ditemukan.',
            'subject.max' => 'Subjek maksimal 100 karakter.',
            'message.required' => 'Pesan harus diisi.',
        ];
    }
}
