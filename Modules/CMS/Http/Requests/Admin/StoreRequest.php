<?php

namespace Modules\CMS\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required|numeric',
            'status' => 'nullable',
            'shop_id' => 'nullable',
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'name is required.',
            'status.required' => 'status is required.',
        ];
    }
}
