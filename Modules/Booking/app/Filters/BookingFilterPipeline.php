<?php

namespace Modules\Booking\Filters;

use App\Filters\BasePipeline;
use Modules\Booking\Filters\Filters\StatusFilter;
use Modules\Booking\Filters\Filters\BookingSearchFilter;

class BookingFilterPipeline extends BasePipeline
{
    protected array $pipes = [
        StatusFilter::class,
        BookingSearchFilter::class,
    ];
}
