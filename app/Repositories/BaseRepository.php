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

    /**
     * Build the query and natively attach eager loads.
     */
    protected function newQuery()
    {
        $query = $this->model->newQuery();
        
        if (!empty($this->with)) {
            $query = $query->with($this->with);
        }
        
        return $query;
    }

    /**
     * Clear active repository query scopes to stop pollution between sequential calls.
     */
    protected function resetScope(): void
    {
        $this->with = [];
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

        $this->resetScope();
        return $result;
    }

    public function find(string|int $id): ?Model
    {
        $key = $this->getCacheKey('find', [$id]);
        $result = Cache::tags($this->getCacheTags())->remember($key, $this->cacheTtl, function () use ($id) {
            return $this->newQuery()->find($id);
        });

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

        $this->resetScope();
        return $result;
    }
}
