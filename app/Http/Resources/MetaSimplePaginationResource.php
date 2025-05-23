<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaSimplePaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->resource->currentPage(),
            'per_page' => $this->resource->perPage(),
            'next_page' => $this->resource->hasMorePages() ? $this->resource->currentPage() + 1 : null,
        ];
    }
}
