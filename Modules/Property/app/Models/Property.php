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
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Property extends Model implements Transformable
{
    use TransformableTrait, HasUuids, SoftDeletes;

    protected $fillable = [
        'host_id', 'user_id', 'title', 'description', 'price_per_night', 'max_guests',
        'bedrooms', 'beds', 'bathrooms', 'address', 'city', 'state', 'country',
        'latitude', 'longitude', 'status', 'reviews_count', 'average_rating'
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order', 'asc');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
