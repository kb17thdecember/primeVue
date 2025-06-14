<?php

namespace Modules\CMS\Http\Requests\Order;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cardholderName' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'productId' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }
}
