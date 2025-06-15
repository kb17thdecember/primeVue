<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Category\CategoryResource;
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
            'description' => $this->description,
            'token_qty' => $this->token_qty,
            'price' => $this->price,
            'image' => $this->image,
            'type' => $this->type->string(),
            'release_date' => $this->release_date,
            'day_available' => $this->day_available
        ];
    }
}
