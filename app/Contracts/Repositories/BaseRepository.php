<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

interface BaseRepository
{
    /**
     * @param Request|null $request
     * @param $subject
     * @return QueryBuilder
     */
    public function handle(?Request $request = null, $subject = null): QueryBuilder;

    /**
     * @param Request|null $request
     * @return Collection
     */
    public function get(?Request $request = null): Collection;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;

    /**
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function updateModel(Model $model, array $attributes): Model;

    /**
     * @param Model $model
     * @return Model
     */
    public function deleteModel(Model $model): Model;

    /**
     * @param Request|null $request
     * @return Collection
     */
    public function limit(?Request $request = null): Collection;
}
