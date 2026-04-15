<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price_per_night' => (float) $this->price_per_night,
            'max_guests' => $this->max_guests,
            'bedrooms' => $this->bedrooms,
            'beds' => $this->beds,
            'bathrooms' => $this->bathrooms,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status?->value ?? $this->status,
            'status_key' => $this->status_key,
            'status_label' => $this->status_label,
            'host' => new UserResource($this->whenLoaded('host')),
            'primary_image' => $this->whenLoaded('primaryImage'),
            'images' => $this->whenLoaded('images'),
            'created_at' => $this->created_at,
        ];
    }
}
