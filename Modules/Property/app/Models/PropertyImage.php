<?php

namespace Modules\Property\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'property_id',
        'image_url',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function getUrlAttribute(): ?string
    {
        return $this->image_url;
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
