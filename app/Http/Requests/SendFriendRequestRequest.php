<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendFriendRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uid' => 'required|integer|exists:jcow_accounts,id',
            'msg' => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'uid.required' => 'User harus diisi.',
            'uid.exists' => 'User tidak ditemukan.',
            'msg.max' => 'Pesan maksimal 200 karakter.',
        ];
    }
}
