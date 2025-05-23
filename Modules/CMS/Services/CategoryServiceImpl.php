<?php

namespace Modules\CMS\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Modules\CMS\Contracts\Repositories\CategoryRepository;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Http\Requests\Category\StoreRequest;

class CategoryServiceImpl implements CategoryService
{
    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        private readonly CategoryRepository $categoryRepository
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

        return $this->categoryRepository->create($data);
    }
}