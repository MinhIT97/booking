<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @var int Cache Time To Live in seconds */
    protected int $cacheTtl = 3600;

    /** @var array Relations to eager load natively */
    protected array $with = [];

    /** @var array Filter scopes to apply to the next query */
    protected array $scopes = [];

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(protected Model $model) {}

    /**
     * Eager load specific relationships.
     */
    public function with(array|string $relations): self
    {
        $this->with = is_string($relations) ? func_get_args() : $relations;
        return $this;
    }

    public function where(string|array|\Closure $column, $operator = null, $value = null): self
    {
        $this->scopes[] = ['method' => 'where', 'params' => func_get_args()];
        return $this;
    }

    public function whereHas(string $relation, \Closure $callback = null, string $operator = '>=', int $count = 1): self
    {
        $this->scopes[] = ['method' => 'whereHas', 'params' => func_get_args()];
        return $this;
    }

    public function withCount(array|string $relations): self
    {
        $this->scopes[] = ['method' => 'withCount', 'params' => func_get_args()];
        return $this;
    }

    public function orderByDesc(string $column): self
    {
        $this->scopes[] = ['method' => 'orderByDesc', 'params' => func_get_args()];
        return $this;
    }

    public function limit(int $value): self
    {
        $this->scopes[] = ['method' => 'limit', 'params' => func_get_args()];
        return $this;
    }

    public function whereMonth(string $column, $operator, $value = null): self
    {
        $this->scopes[] = ['method' => 'whereMonth', 'params' => func_get_args()];
        return $this;
    }

    public function whereYear(string $column, $operator, $value = null): self
    {
        $this->scopes[] = ['method' => 'whereYear', 'params' => func_get_args()];
        return $this;
    }

    public function get(): Collection
    {
        $result = $this->newQuery()->get();
        $this->resetScope();
        return $result;
    }

    public function count(): int
    {
        $result = $this->newQuery()->count();
        $this->resetScope();
        return $result;
    }

    public function avg(string $column)
    {
        $result = $this->newQuery()->avg($column);
        $this->resetScope();
        return $result;
    }

    public function sum(string $column)
    {
        $result = $this->newQuery()->sum($column);
        $this->resetScope();
        return $result;
    }

    /**
     * Build the query and natively attach eager loads.
     */
    protected function newQuery()
    {
        $query = $this->model->newQuery();

        if (!empty($this->with)) {
            $query = $query->with($this->with);
        }

        foreach ($this->scopes as $scope) {
            $query = $query->{$scope['method']}(...$scope['params']);
        }

        return $query;
    }

    /**
     * Clear active repository query scopes to stop pollution between sequential calls.
     */
    protected function resetScope(): void
    {
        $this->with = [];
        $this->scopes = [];
    }

    /**
     * Generate a unique cache key functionally incorporating specific Eager Loads!
     */
    protected function getCacheKey(string $operation, array $params = []): string
    {
        $table = $this->model->getTable();
        $params['with'] = $this->with; // Include eager load context inside the cache string hash

        $paramsString = md5(json_encode($params));
        return "{$table}_{$operation}_{$paramsString}";
    }

    protected function getCacheTags(): array
    {
        return [$this->model->getTable()];
    }

    protected function clearCache(): void
    {
        Cache::tags($this->getCacheTags())->flush();
    }

    public function all(): Collection
    {
        $key = $this->getCacheKey('all');
        $result = Cache::tags($this->getCacheTags())->remember($key, $this->cacheTtl, function () {
            return $this->newQuery()->get();
        });

        if ($result instanceof \__PHP_Incomplete_Class) {
            Cache::tags($this->getCacheTags())->forget($key);
            $result = $this->newQuery()->get();
        }

        $this->resetScope();
        return $result;
    }

    public function find(string|int $id): ?Model
    {
        $key = $this->getCacheKey('find', [$id]);
        $result = Cache::tags($this->getCacheTags())->remember($key, $this->cacheTtl, function () use ($id) {
            return $this->newQuery()->find($id);
        });

        if ($result instanceof \__PHP_Incomplete_Class) {
            Cache::tags($this->getCacheTags())->forget($key);
            $result = $this->newQuery()->find($id);
        }

        $this->resetScope();
        return $result;
    }

    public function create(array $data): Model
    {
        $record = $this->model->create($data);
        $this->clearCache();
        return $record;
    }

    public function update(string|int $id, array $data): bool
    {
        $record = $this->model->find($id);

        if (!$record) {
            return false;
        }

        $updated = $record->update($data);
        if ($updated) {
            $this->clearCache();
        }

        return $updated;
    }

    public function delete(string|int $id): bool
    {
        $record = $this->model->find($id);

        if (!$record) {
            return false;
        }

        $deleted = $record->delete();
        if ($deleted) {
            $this->clearCache();
        }

        return $deleted;
    }

    public function findBy(array $criteria): Collection
    {
        $key = $this->getCacheKey('findBy', $criteria);
        $result = Cache::tags($this->getCacheTags())->remember($key, $this->cacheTtl, function () use ($criteria) {
            return $this->newQuery()->where($criteria)->get();
        });

        if ($result instanceof \__PHP_Incomplete_Class) {
            Cache::tags($this->getCacheTags())->forget($key);
            $result = $this->newQuery()->where($criteria)->get();
        }

        $this->resetScope();
        return $result;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $key = $this->getCacheKey('paginate', ['perPage' => $perPage, 'page' => $page]);
        $result = Cache::tags($this->getCacheTags())->remember($key, $this->cacheTtl, function () use ($perPage) {
            return $this->newQuery()->paginate($perPage);
        });

        if ($result instanceof \__PHP_Incomplete_Class || !($result instanceof LengthAwarePaginator)) {
            Cache::tags($this->getCacheTags())->forget($key);
            $result = $this->newQuery()->paginate($perPage);
        }

        $this->resetScope();
        return $result;
    }
}
