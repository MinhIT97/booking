<?php

namespace Modules\Booking\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'user_id' => $this->user_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'total_price' => (float) $this->total_price,
            'status' => $this->status?->value ?? $this->status,
            'status_key' => $this->status_key,
            'status_label' => $this->status_label,
            // Eagerly loaded property relationship nested resource mapping
            'property' => $this->whenLoaded('property'),
            'created_at' => $this->created_at,
        ];
    }
}
