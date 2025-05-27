<?php

namespace Modules\CMS\Http\Requests\Category;

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
            'status' => 'required',
            'display_order' => 'nullable|numeric',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'description' => 'nullable|string',
            'updated_at' => 'nullable|date_format:Y-m-d H:i:s',
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
        ];
    }
}
