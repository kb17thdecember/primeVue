<?php

namespace Modules\CMS\Http\Requests\Product;

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
            'image' => 'nullable|array',
            'image.*' => 'file|mimes:jpg,jpeg,png',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'release_date' => 'nullable',
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
