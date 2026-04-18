<?php

namespace Modules\Admin\Filters;

use App\Filters\BasePipeline;
use Modules\Property\Filters\Filters\StatusFilter;
use Modules\Admin\Filters\Filters\AdminPropertySearchFilter;

class AdminPropertyFilterPipeline extends BasePipeline
{
    protected array $pipes = [
        StatusFilter::class,
        AdminPropertySearchFilter::class,
    ];
}
