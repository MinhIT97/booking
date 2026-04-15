<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Booking\Enums\BookingStatus;

class Booking extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    /**
     * Disable mass assignment vulnerability locks natively so the repository handles the injection securely.
     */
    protected $guarded = [];


    protected $fillable = [
        'property_id',
        'user_id',
        'check_in_date',
        'check_out_date',
        'guests',
        'total_price',
        'status',
    ];

    // Optional native casts
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'status' => BookingStatus::class,
    ];

    protected function statusKey(): Attribute
    {
        return Attribute::get(fn () => $this->status?->key() ?? BookingStatus::Pending->key());
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => $this->status?->label() ?? BookingStatus::Pending->label());
    }

    public function property()
    {
        return $this->belongsTo(\Modules\Property\Models\Property::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
