<?php

namespace Modules\Admin\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Modules\Property\Enums\PropertyStatus;

class AdminPropertyFilterCriteria implements CriteriaInterface
{
    public function __construct(protected array $filters) {}

    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->filters['status'])) {
            $status = PropertyStatus::fromInput($this->filters['status']);
            if ($status) {
                $model = $model->where('status', $status->value);
            }
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $model = $model->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('host', function($h) use ($search) {
                      $h->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $model->latest();
    }
}
