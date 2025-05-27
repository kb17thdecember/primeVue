<?php

namespace Modules\CMS\Contracts\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Modules\CMS\Http\Requests\Category\StoreRequest;

interface CategoryService
{
    /**
     * @return Collection
     */
    public function getAllCategories(): Collection;

    /**
     * @param StoreRequest $request
     * @return Category
     */
    public function store(StoreRequest $request): Category;

    /**
     * @param int $category
     * @return Category
     */
    public function edit(int $category): Category;

    /**
     * @return Collection
     */
    public function getParentId(): Collection;
}
