<?php

namespace Modules\CMS\Contracts\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Http\Requests\Category\StoreRequest;
use Modules\CMS\Http\Requests\Category\UpdateRequest;

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
     * @param int $category
     * @param UpdateRequest $request
     * @return Model
     */
    public function update(int $category, UpdateRequest $request): Model;

    /**
     * @param int $category
     * @return Model
     */
    public function delete(int $category): Model;

    /**
     * @return Collection
     */
    public function getParent(): Collection;
}
