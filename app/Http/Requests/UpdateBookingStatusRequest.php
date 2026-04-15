<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Booking\Enums\BookingStatus;

class UpdateBookingStatusRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('status')) {
            $status = BookingStatus::fromInput($this->input('status'));

            if ($status) {
                $this->merge(['status' => $status->value]);
            }
        }
    }

    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(BookingStatus::class)],
        ];
    }
}
