<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaCursorPaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'per_page' => $this->resource->perPage(),
            'next_cursor' => $this->resource->nextCursor()?->encode(),
            'prev_cursor' => $this->resource->previousCursor()?->encode(),
        ];
    }
}
