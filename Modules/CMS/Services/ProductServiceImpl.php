<?php

namespace Modules\CMS\Services;

use App\Enums\ProductType;
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
use Modules\CMS\Contracts\Repositories\ShopRepository;
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
        $condition = new Request();

        return $this->productRepository->handle($condition)->get();
    }

    /**
     * @return array
     */
    public function getAllPricing(): array
    {
        try {
            $result = [];

            $allProducts = $this->getAllProducts();

            $productTypes = ProductType::stringNames();

            foreach ($productTypes as $productTypeKey => $productTypeName) {
                $products = $allProducts->filter(function ($product) use ($productTypeKey) {
                    return $product->type->value == $productTypeKey;
                });

                if (count($products)) {
                    $result[] = [
                        'typeName' => $productTypeName,
                        'typeId' => $productTypeKey,
                        'products' => $products->transform(function ($product) {
                            $product->typeName = $product->type->string();

                            return $product;
                        })->toArray()
                    ];
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' error: ' . $e->getMessage());
            Log::error($e);

            return [];
        }
    }

    /**
     * @param StoreRequest $request
     * @return Product
     */
    public function store(StoreRequest $request): Product
    {
        $data = $request->validated();

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
            'id' => $product
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

        $data['release_date'] = Carbon::parse($data['release_date'])->format('Y-m-d');

        $images = $request->file('image');

        if ($images) {
            $path = StoragePrefix::PRODUCT;
            $name = (string)time() . '_' . Str::random(8);
            $data['image'] = $this->uploadMultipleImages($images, $path, $name);
        } else {
            $data['image'] = $productData->image ?? [];
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
