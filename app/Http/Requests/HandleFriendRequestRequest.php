<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandleFriendRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uid' => 'required|integer|exists:jcow_accounts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'uid.required' => 'User harus diisi.',
            'uid.exists' => 'User tidak ditemukan.',
        ];
    }
}
