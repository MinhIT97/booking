<?php

namespace Modules\Admin\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Enums\UserStatus;

class AdminUserFilterCriteria implements CriteriaInterface
{
    public function __construct(protected array $filters) {}

    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->filters['role'])) {
            $model = $model->whereHas('role', function($q) {
                $q->where('name', $this->filters['role']);
            });
        }

        if (!empty($this->filters['status'])) {
            $status = UserStatus::fromInput($this->filters['status']);
            if ($status) {
                $model = $model->where('status', $status->value);
            }
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $model = $model->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $model->latest();
    }
}
