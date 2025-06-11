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
            'admin_id' => 'required',
            'subdomain' => 'nullable',
            'prefecture' => 'required|string',
            'address_1' => 'string|nullable',
            'address_2' => 'string|nullable',
            'phone_number' => 'required|numeric|digits_between:10,11',
            'status' => 'required',
            'api_key' => 'required|string|max:50',
            'channel_access_token' => 'nullable|string',
            'channel_secret' => 'nullable|string',
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
