<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

class ResourceCollection extends BaseResourceCollection
{
    /**
     * Define resource class to collect data.
     * Example: UserResource::class
     *
     * @var string
     */
    public $collects;

    /**
     * Create a paginate-aware HTTP response.
     */
    protected function preparePaginatedResponse($request): JsonResponse
    {
        return (new PaginatedResourceResponse($this))->toResponse($request);
    }
}
