<?php

namespace Modules\CMS\Services;

use App\Enums\StoragePrefix;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\CMS\Contracts\Repositories\CategoryRepository;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Contracts\Services\StorageService;
use Modules\CMS\Http\Requests\Category\StoreRequest;
use Modules\CMS\Http\Requests\Category\UpdateRequest;

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
        return $this->categoryRepository->handle(new Request(['sort' => 'display_order']))->get();
    }

    /**
     * @param StoreRequest $request
     * @return Category
     */
    public function store(StoreRequest $request): Category
    {
        $data = $request->validated();
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

        $image = $request->file('image');
        if ($image) {
            $path = StoragePrefix::CATEGORY;
            $name = (string)time();
            $data['image'] = $this->uploadImage($image, $path, $name);
        }

        return $this->categoryRepository->create($data);
    }

    /**
     * @param int $category
     * @return Category
     */
    public function edit(int $category): Category
    {
        return $this->categoryRepository->handle(new Request(['id' => $category, 'include' => 'parent']))->firstOrFail();
    }

    /**
     * @param int $category
     * @param UpdateRequest $request
     * @return Model
     */
    public function update(int $category, UpdateRequest $request): Model
    {
        $category = $this->categoryRepository->handle(new Request(['id' => $category]))->firstOrFail();
        $data = $request->validated();

        $image = $request->file('image');
        if ($image) {
            $path = StoragePrefix::CATEGORY;
            $name = (string)time();
            $data['image'] = $this->uploadImage($image, $path, $name);
        }

        return $this->categoryRepository->updateModel($category, $data);
    }

    /**
     * @param int $category
     * @return Model
     */
    public function delete(int $category): Model
    {
        $categories = $this->categoryRepository->handle(new Request(['id' => $category]))->firstOrFail();

        return $this->categoryRepository->deleteModel($categories);
    }

    /**
     * @return Collection
     */
    public function getParent(): Collection
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
