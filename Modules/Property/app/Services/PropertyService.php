<?php

namespace Modules\Property\Services;

use App\Services\BaseService;
use Modules\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PropertyService extends BaseService
{
    /**
     * Inject our explicit Repository interface, keeping the Service completely abstracted from DB calls.
     */
    public function __construct(protected PropertyRepositoryInterface $repository) {}

    public function getAllProperties(): LengthAwarePaginator
    {
        // Business boundary: caching mechanisms, applying generalized filters, etc.
        return $this->repository->with(['user', 'images'])->paginate(15);
    }

    public function getPropertyById(string $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function createProperty(array $data): Model
    {
        // Execute flawlessly inside our custom BaseService transaction!
        return $this->executeInTransaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function updateProperty(string $id, array $data): bool
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }

    public function deleteProperty(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
