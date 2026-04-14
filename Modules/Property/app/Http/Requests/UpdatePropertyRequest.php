<?php

namespace Modules\Property\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'max_guests' => 'sometimes|integer|min:1',
            'bedrooms' => 'sometimes|integer|min:0',
            'beds' => 'sometimes|integer|min:0',
            'bathrooms' => 'sometimes|integer|min:0',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:pending,approved,rejected',
        ];
    }
}
