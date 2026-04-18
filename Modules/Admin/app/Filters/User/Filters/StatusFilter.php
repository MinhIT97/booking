<?php

namespace Modules\Admin\Filters\User\Filters;

use App\Filters\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\UserStatus;

class StatusFilter implements FilterContract
{
    public function apply(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['status'] ?? null,
            function($q, $input) {
                $status = UserStatus::fromInput($input);
                return $status ? $q->where('status', $status->value) : $q;
            }
        );
    }
}
