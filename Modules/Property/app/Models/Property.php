<?php

namespace Modules\Property\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Property.
 *
 * @package namespace Modules\Property\Models;
 */
class Property extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
