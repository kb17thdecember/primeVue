<?php

namespace Modules\CMS\Http\Requests\Product;

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
            'category_id' => 'nullable',
            'brand_id' => 'nullable',
            'display_order' => 'nullable|numeric',
            'image' => 'nullable|array',
            'image.*' => [
                ...($this->hasFile('image') ? ['file', 'mimes:jpeg,png', 'max:5120'] : ['string', 'url']),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'discount_code' => 'nullable|string',
            'quantity' => 'required|numeric',
            'release_date' => 'nullable',
            'tag' => 'nullable',
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
            'name.required' => 'Product name is required.',
            'status.required' => 'Product status is required.',
        ];
    }
}
