<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'receiver_id' => 'required|uuid|exists:users,id',
            'property_id' => 'nullable|uuid|exists:properties,id',
            'content' => 'required|string|max:1000',
        ];
    }
}
