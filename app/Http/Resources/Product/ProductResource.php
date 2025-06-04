<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount,
            'image' => $this->image,
            'tag' => $this->tag,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'quantity' => $this->quantity,
            'release_date' => $this->release_date,
        ];
    }
}
