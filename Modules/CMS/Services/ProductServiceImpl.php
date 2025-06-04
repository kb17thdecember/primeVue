<?php

namespace Modules\CMS\Services;

use App\Enums\StoragePrefix;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Http\Requests\Product\StoreRequest;
use Modules\CMS\Http\Requests\Product\UpdateRequest;

class ProductServiceImpl implements ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly StorageService $storageService,
    ) {}

    /**
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        $condition = new Request([
            'include' => 'category,brand'
        ]);
        return $this->productRepository->handle($condition)->get();
    }

    /**
     * @param StoreRequest $request
     * @return Product
     */
    public function store(StoreRequest $request): Product
    {
        $data = $request->validated();
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

        $images = $request->file('image');
        if ($images) {
            $path = StoragePrefix::PRODUCT;
            $name = (string)time() . '_' . Str::random(8);
            $data['image'] = $this->uploadMultipleImages($images, $path, $name);
        } else {
            $data['image'] = [];
        }

        return $this->productRepository->create($data);
    }

    /**
     * @param int $product
     * @return Model
     */
    public function edit(int $product): Model
    {
        $condition = new Request([
            'id' => $product,
            'include' => 'category,brand,category.parent,category.children'
        ]);
        return $this->productRepository->handle($condition)->firstOrFail();
    }

    /**
     * @param int $product
     * @param UpdateRequest $request
     * @return Model
     */
    public function update(int $product, UpdateRequest $request): Model
    {
        $productData = $this->productRepository->handle(new Request(['id' => $product]))->firstOrFail();
        $data = $request->validated();
        $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

        $images = $request->file('image');
        if ($images) {
            $path = StoragePrefix::PRODUCT;
            $name = (string)time() . '_' . Str::random(8);
            $data['image'] = $this->uploadMultipleImages($images, $path, $name);
        } else {
            $data['image'] = [];
        }

        return $this->productRepository->updateModel($productData, $data);
    }

    /**
     * @param int $product
     * @return Model
     */
    public function delete(int $product): Model
    {
        $products = $this->productRepository->handle(new Request(['id' => $product]))->firstOrFail();

        return $this->productRepository->deleteModel($products);
    }

    /**
     * @param array $images
     * @param string $path
     * @param string $prefix
     * @return array
     */
    private function uploadMultipleImages(array $images, string $path, string $prefix): array
    {
        $result = [];
        foreach ($images as $index => $image) {
            if (!$image instanceof UploadedFile) {
                Log::warning("Invalid file at index {$index} in uploadMultipleImages");
                continue;
            }
            $name = $prefix . '_' . $index . '_' . Str::random(4);
            $uploadedPath = $this->storageService->uploadFile($image, $path, $name);
            if ($uploadedPath) {
                $result[] = $uploadedPath;
            } else {
                Log::error("Failed to upload file at index {$index}: {$image->getClientOriginalName()}");
            }
        }
        return $result;
    }
}
