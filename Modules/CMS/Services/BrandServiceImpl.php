<?php

namespace Modules\CMS\Services;

use App\Enums\StoragePrefix;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\CMS\Contracts\Repositories\BrandRepository;
use Modules\CMS\Contracts\Services\BrandService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Http\Requests\Brand\StoreRequest;

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