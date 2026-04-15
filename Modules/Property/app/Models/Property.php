<?php

namespace Modules\Property\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;
use Modules\Property\Enums\PropertyStatus;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'host_id',
        'user_id',
        'title',
        'description',
        'price_per_night',
        'max_guests',
        'bedrooms',
        'beds',
        'bathrooms',
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'status',
        'reviews_count',
        'average_rating'
    ];

    protected $casts = [
        'status' => PropertyStatus::class,
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


    protected function statusBadge(): Attribute
    {

        return Attribute::get(function () {
            return match ($this->status) {
                PropertyStatus::Active => 'bg-green-500',
                PropertyStatus::Draft  => 'bg-amber-400',
                PropertyStatus::Rejected => 'bg-red-500',
                default                => 'bg-gray-400',
            };
        });
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(function () {
            return $this->status?->label() ?? 'Draft';
        });
    }

    protected function statusKey(): Attribute
    {
        return Attribute::get(function () {
            return $this->status?->key() ?? 'draft';
        });
    }
}
