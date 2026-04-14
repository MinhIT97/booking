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
