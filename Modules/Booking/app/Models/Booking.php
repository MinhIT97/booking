<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Disable mass assignment vulnerability locks natively so the repository handles the injection securely.
     */
    protected $guarded = [];

    // Optional native casts
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(\Modules\Property\Models\Property::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
