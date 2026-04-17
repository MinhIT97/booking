<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Eager load specific relationships.
     */
    public function with(array|string $relations): self;

    /**
     * Add a where clause to the repository query.
     */
    public function where(string|array|\Closure $column, $operator = null, $value = null): self;

    /**
     * Add a whereHas clause to the repository query.
     */
    public function whereHas(string $relation, \Closure $callback = null, string $operator = '>=', int $count = 1): self;

    /**
     * Add a whereDoesntHave clause to the repository query.
     */
    public function whereDoesntHave(string $relation, \Closure $callback = null): self;

    /**
     * Add a withCount clause to the repository query.
     */
    public function withCount(array|string $relations): self;

    /**
     * Add an orderByDesc clause to the repository query.
     */
    public function orderByDesc(string $column): self;

    /**
     * Add a limit clause to the repository query.
     */
    public function limit(int $value): self;

    /**
     * Add a whereMonth clause to the repository query.
     */
    public function whereMonth(string $column, $operator, $value = null): self;

    /**
     * Add a whereYear clause to the repository query.
     */
    public function whereYear(string $column, $operator, $value = null): self;

    /**
     * Execute the query and get results.
     */
    public function get(): Collection;

    /**
     * Get the count of records.
     */
    public function count(): int;

    /**
     * Get the average value of a column.
     */
    public function avg(string $column);

    /**
     * Get the sum of a column.
     */
    public function sum(string $column);

    /**
     * Retrieve all records.
     */
    public function all(): Collection;

    /**
     * Find a specifically identified record.
     */
    public function find(string|int $id): ?Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update an identified record.
     */
    public function update(string|int $id, array $data): bool;

    /**
     * Delete an identified record.
     */
    public function delete(string|int $id): bool;

    /**
     * Find records based on specific criteria.
     */
    public function findBy(array $criteria): Collection;

    /**
     * Paginate records.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;
}
