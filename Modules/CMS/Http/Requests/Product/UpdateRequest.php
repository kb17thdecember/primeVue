<?php

namespace Modules\CMS\Http\Requests\Product;

use App\Enums\ProductType;
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
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'release_date' => 'nullable',
            'price' => 'required|numeric',
            'token_qty' => 'required|numeric',
            'type' => 'required|in:' . implode(',', array_keys(ProductType::values())),
            'stripe_product_id' => 'required|string'
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
        ];
    }
}
