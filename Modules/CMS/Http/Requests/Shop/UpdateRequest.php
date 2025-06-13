<?php

namespace Modules\CMS\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone_number' => 'nullable|digits_between:10,11',
            'status' => 'required',
            'api_key' => 'required|string|max:50',
            'request_key_flag' => 'nullable',
            'update_at' => 'nullable|string'
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
            'name.required' => 'Category name is required.',
            'status.required' => 'Category status is required.',
        ];
    }
}
