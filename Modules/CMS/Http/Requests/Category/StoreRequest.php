<?php

namespace Modules\CMS\Http\Requests\Category;

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
            'status' => 'required',
            'parent_id' => 'nullable',
            'display_order' => 'nullable|numeric',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
            'description' => 'nullable|string',
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
