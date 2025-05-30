<?php

namespace Modules\CMS\Services;

use App\Enums\StoragePrefix;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\CMS\Contracts\Repositories\BrandRepository;
use Modules\CMS\Contracts\Services\BrandService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Http\Requests\Brand\StoreRequest;
use Modules\CMS\Http\Requests\Brand\UpdateRequest;

class BrandServiceImpl implements BrandService
{
    public function __construct(
        private readonly BrandRepository $brandRepository,
        private readonly StorageService $storageService,
    ){}

    /**
     * @return Collection
     */
    public function getAllBrands(): Collection
    {
        return $this->brandRepository->handle(new Request(['sort' => 'display_order']))->get();
    }

    /**
     * @param StoreRequest $request
     * @return Brand
     */
    public function store(StoreRequest $request): Brand
    {
        $data = $request->validated();
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

        $image = $request->file('logo');
        if ($image) {
            $path = StoragePrefix::BRAND;
            $name = (string)time();
            $data['logo'] = $this->uploadImage($image, $path, $name);
        }

        return $this->brandRepository->create($data);
    }

    /**
     * @param int $brand
     * @return Brand
     */
    public function edit(int $brand): Brand
    {
        return $this->brandRepository->handle(new Request(['id' => $brand]))->firstOrFail();
    }

    /**
     * @param int $brand
     * @param UpdateRequest $request
     * @return Model
     */
    public function update(int $brand, UpdateRequest $request): Model
    {
        $brandData = $this->brandRepository->handle(new Request(['id' => $brand]))->firstOrFail();
        $data = $request->validated();

        $logo = $request->file('logo');
        if ($logo) {
            $path = StoragePrefix::BRAND;
            $name = (string)time();
            $data['logo'] = $this->uploadImage($logo, $path, $name);
        }

        return $this->brandRepository->updateModel($brandData, $data);
    }

    /**
     * @param int $brand
     * @return Model
     */
    public function delete(int $brand): Model
    {
        $brands = $this->brandRepository->handle(new Request(['id' => $brand]))->firstOrFail();

        return $this->brandRepository->deleteModel($brands);
    }

    /**
     * @param UploadedFile $image
     * @param string $path
     * @param string $name
     * @return string
     */
    private function uploadImage(UploadedFile $image, string $path, string $name): string
    {
        $this->storageService->uploadFile(file: $image, path: $path, name: $name);

        return sprintf('%s/%s.%s', $path, $name, $image->getClientOriginalExtension());
    }
}