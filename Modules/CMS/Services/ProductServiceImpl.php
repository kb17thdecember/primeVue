<?php

namespace Modules\CMS\Services;

use App\Enums\StoragePrefix;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Http\Requests\Product\StoreRequest;

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
        return $this->productRepository->handle()->get();
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
            $name = (string)time();
            $data['image'] = $this->uploadMultipleImages($images, $path, $name);
        }

        return $this->productRepository->create($data);
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
                continue;
            }
            $name = $prefix . '_' . $index;
            $uploadedPath = $this->storageService->uploadFile($image, $path, $name);
            if ($uploadedPath) {
                $result[] = $uploadedPath;
            }
        }
        return $result;
    }

}
