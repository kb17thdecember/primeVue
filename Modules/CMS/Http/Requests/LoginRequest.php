<?php

namespace Modules\CMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember_token' => ['nullable', 'boolean']
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'email.required' => 'ユーザー名は必須項目です。',
            'password.required' => 'パスワードは必須です。',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('account.field.email'),
            'password' => __('account.field.password'),
        ];
    }
}
