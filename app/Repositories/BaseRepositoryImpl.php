<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepository;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepositoryImpl implements BaseRepository
{
    protected string $model;

    protected array $includes = [];

    protected array $sorts = [];

    protected array $filters = [];

    public function __construct()
    {
        $this->setAllowedFilters();
    }

    abstract protected function setAllowedFilters();

    /**
     * Common handle for function related request.
     *
     * @param Request|null $request
     * @param $subject
     * @return QueryBuilder
     */
    public function handle(?Request $request = null, $subject = null): QueryBuilder
    {
        return QueryBuilder::for($subject ?: $this->model, $request)
            ->allowedFilters($this->filters)
            ->allowedIncludes($this->includes)
            ->allowedSorts($this->sorts);
    }

    /**
     * @param Request|null $request
     * @return Collection
     */
    public function get(?Request $request = null): Collection
    {
        return $this->handle($request)->get();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed
    {
        return $this->model::create($attributes);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function updateModel(Model $model, array $attributes): Model
    {
        $model->update($attributes);

        return $model;
    }

    /**
     * @param Model $model
     * @return Model
     */
    public function deleteModel(Model $model): Model
    {
        $model->delete();

        return $model;
    }

    /**
     * @param Request|null $request
     * @return Collection
     */
    public function limit(?Request $request = null): Collection
    {
        return $this->handle($request)->limit($request?->get('limit', 10) ?? 10)->get();
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Model
     */
    public function updateOrCreate(array $attributes = [], array $values): Model
    {
        return $this->model::query()->updateOrCreate(attributes: $attributes ?? [], values: $values);
    }
}
