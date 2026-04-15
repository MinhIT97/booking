<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'check_in_date' => $this->check_in_date->format('Y-m-d'),
            'check_out_date' => $this->check_out_date->format('Y-m-d'),
            'guests' => $this->guests,
            'total_price' => (float) $this->total_price,
            'status' => $this->status?->value ?? $this->status,
            'status_key' => $this->status_key,
            'status_label' => $this->status_label,
            'property' => new PropertyResource($this->whenLoaded('property')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
