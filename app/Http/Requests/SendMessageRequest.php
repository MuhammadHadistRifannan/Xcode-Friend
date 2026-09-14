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
            'message' => 'nullable|string|required_without:attachment',
            'attachment' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'reply_to' => 'nullable|integer|exists:jcow_messages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_id.required' => 'Penerima harus diisi.',
            'recipient_id.exists' => 'Penerima tidak ditemukan.',
            'subject.max' => 'Subjek maksimal 100 karakter.',
            'message.required_without' => 'Pesan atau gambar harus diisi.',
            'attachment.image' => 'File lampiran harus berupa gambar.',
            'attachment.mimes' => 'Format gambar harus JPG, JPEG, PNG, WEBP, atau GIF.',
            'attachment.max' => 'Ukuran gambar maksimal 5 MB.',
        ];
    }
}
