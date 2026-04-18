<?php

namespace Modules\Property\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Modules\Property\Enums\PropertyStatus;

class HostPropertiesCriteria implements CriteriaInterface
{
    public function __construct(protected string $hostId, protected array $filters = []) {}

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('host_id', $this->hostId);

        if (!empty($this->filters['q'])) {
            $q = '%' . $this->filters['q'] . '%';
            $model = $model->where(fn($b) => $b->where('title', 'like', $q)->orWhere('city', 'like', $q));
        }

        if (!empty($this->filters['property_type_id'])) {
            $model = $model->where('property_type_id', $this->filters['property_type_id']);
        }

        if (!empty($this->filters['status'])) {
            $status = PropertyStatus::fromInput($this->filters['status']);
            if ($status) {
                $model = $model->where('status', $status->value);
            }
        }

        return $model->orderByDesc('created_at');
    }
}
