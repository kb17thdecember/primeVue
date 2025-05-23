<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

class PaginatedResourceResponse extends ResourceResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return \Illuminate\Support\HigherOrderTapProxy
     */
    public function toResponse($request): \Illuminate\Support\HigherOrderTapProxy
    {
        return tap(
            response()->json(
                $this->wrap(
                    $this->resource->resolve($request),
                    array_merge_recursive(
                        $this->paginationInformation($request),
                        $this->resource->with($request),
                        $this->resource->additional
                    )
                ),
                $this->calculateStatus(),
                [],
                $this->resource->jsonOptions()
            ),
            function ($response) use ($request) {
                $response->original = $this->resource->resource->map(
                    fn ($item) => is_array($item) ? Arr::get($item, 'resource') : $item->resource
                );

                $this->resource->withResponse($request, $response);
            }
        );
    }

    /**
     * Add the pagination information to the response.
     *
     * @param Request $request
     * @return array
     */
    protected function paginationInformation($request): array
    {
        return [
            'meta' => $this->meta($request),
        ];
    }

    public function meta(Request $request): array
    {
        return match (true) {
            $this->resource->resource instanceof LengthAwarePaginator
            => (new MetaPaginationResource($this->resource->resource))->toArray($request),
            $this->resource->resource instanceof CursorPaginator
            => (new MetaCursorPaginationResource($this->resource->resource))->toArray($request),
            $this->resource->resource instanceof Paginator
            => (new MetaSimplePaginationResource($this->resource->resource))->toArray($request),
            default => [],
        };
    }
}
