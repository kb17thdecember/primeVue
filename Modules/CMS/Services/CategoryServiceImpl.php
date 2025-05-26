<?php

namespace Modules\CMS\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\CMS\Contracts\Repositories\CategoryRepository;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Http\Requests\Category\StoreRequest;
use Illuminate\Support\Str;

class CategoryServiceImpl implements CategoryService
{
    /**
     * @param CategoryRepository $categoryRepository
     * @param StorageService $storageService
     */
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly StorageService $storageService,
    ){}

    /**
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->get();
    }

    /**
     * @param StoreRequest $request
     * @return Category
     */
    public function storeCategory(StoreRequest $request): Category
    {
        $data = $request->validated();

        $image = $request->file('image');

        if ($image) {
            $path = 'categories';
            $name = str_replace('-', '_', Str::slug($data['name']));;
            $imagePath = $this->uploadImage($image, $path, $name);
            $data['image_path'] = $imagePath;
        }

        return $this->categoryRepository->create($data);
    }

    /**
     * @return Collection
     */
    public function getParentId(): Collection
    {
        return $this->categoryRepository->handle(new Request(['parent_id' => ['null' => true]]))->get();
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