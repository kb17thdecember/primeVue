<?php

namespace App\Http\Resources\SubscriberHistory;

use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberHistoryResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_data' => $this->payment_data,
            'shop_id' => $this->shop_id,
            'shop_name' => $this->shop?->name,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'price' => $this->price,
            'token_qty' => $this->token_qty,
            'type' => $this->type->string(),
            'payment_status' => $this->payment_status->string(),
            'buy_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
