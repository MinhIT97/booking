<?php

namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Modules\Booking\Models\Booking;

class AdminBookingRepository extends BaseRepository implements AdminBookingRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Booking::class;
    }
}
