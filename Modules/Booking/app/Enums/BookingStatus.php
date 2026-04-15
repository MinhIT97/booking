<?php

namespace Modules\Booking\Enums;

enum BookingStatus: int
{
    case Pending = 1;
    case Confirmed = 2;
    case Cancelled = 3;
    case Completed = 4;

    public function key(): string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Confirmed => 'confirmed',
            self::Cancelled => 'cancelled',
            self::Completed => 'completed',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }

    public static function fromInput(mixed $value): ?self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_numeric($value)) {
            return self::tryFrom((int) $value);
        }

        return match (strtolower((string) $value)) {
            'pending' => self::Pending,
            'confirmed', 'confirm' => self::Confirmed,
            'cancelled', 'canceled', 'cancel' => self::Cancelled,
            'completed', 'complete' => self::Completed,
            default => null,
        };
    }
}
