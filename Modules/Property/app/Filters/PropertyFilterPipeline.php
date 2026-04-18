<?php

namespace Modules\Property\Filters;

use App\Filters\BasePipeline;
use Modules\Property\Filters\Filters\StatusFilter;
use Modules\Property\Filters\Filters\TypeFilter;
use Modules\Property\Filters\Filters\LocationSearchFilter;
use Modules\Property\Filters\Filters\PriceRangeFilter;
use Modules\Property\Filters\Filters\AvailabilityFilter;
use Modules\Property\Filters\Filters\RatingFilter;
use Modules\Property\Filters\Filters\SortFilter;

class PropertyFilterPipeline extends BasePipeline
{
    protected array $pipes = [
        StatusFilter::class,
        TypeFilter::class,
        LocationSearchFilter::class,
        PriceRangeFilter::class,
        AvailabilityFilter::class,
        RatingFilter::class,
        SortFilter::class,
    ];
}
