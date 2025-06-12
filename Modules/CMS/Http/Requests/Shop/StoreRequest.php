<?php

namespace Modules\CMS\Http\Requests\Shop;

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
            'admin_id' => 'required',
            'province' => 'required|string',
            'prefecture' => 'required|string',
            'town' => 'string|nullable',
            'address' => 'string|nullable',
            'phone_number' => 'required|numeric',
            'status' => 'required',
            'api_key' => 'required|string',
            'created_at' => 'nullable|string'
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
