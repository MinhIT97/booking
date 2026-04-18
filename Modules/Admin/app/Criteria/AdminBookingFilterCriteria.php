<?php

namespace Modules\Admin\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Modules\Booking\Enums\BookingStatus;

class AdminBookingFilterCriteria implements CriteriaInterface
{
    public function __construct(protected array $filters) {}

    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->filters['status'])) {
            $status = BookingStatus::fromInput($this->filters['status']);
            if ($status) {
                $model = $model->where('status', $status->value);
            }
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $model = $model->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($user) => $user
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('property', fn ($property) => $property
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%"));
            });
        }

        return $model->latest();
    }
}
