<?php

namespace Modules\Booking\DTOs;

use App\DTOs\BaseDTO;
use Illuminate\Http\Request;

class BookingDTO extends BaseDTO
{
    public string $propertyId;
    public string $userId;
    public string $checkInDate;
    public string $checkOutDate;

    public static function fromRequest(Request $request): self
    {
        // Extracts securely validated keys directly into isolated class properties!
        return new self([
            'propertyId' => $request->validated('property_id'),
            'userId' => (string) $request->user()->id,
            'checkInDate' => $request->validated('check_in_date'),
            'checkOutDate' => $request->validated('check_out_date'),
        ]);
    }
}
