<?php

namespace Modules\Property\Enums;

enum PropertyStatus: int
{
    case Draft  = 1;
    case Active = 2;
    case Rejected = 3;

    /**
     * Human-readable label for display in UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft  => 'Draft',
            self::Active => 'Active',
            self::Rejected => 'Rejected',
        };
    }

    public function key(): string
    {
        return match ($this) {
            self::Draft => 'draft',
            self::Active => 'active',
            self::Rejected => 'rejected',
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
            'draft', 'pending', 'inactive' => self::Draft,
            'active', 'approved' => self::Active,
            'rejected' => self::Rejected,
            default => null,
        };
    }

    /**
     * Default status when creating a new property.
     */
    public static function default(): self
    {
        return self::Active;
    }
}
