<?php

namespace App\Enums;

enum UserStatus: int
{
    case Pending = 1;
    case Active = 2;
    case Inactive = 3;
    case Blocked = 4;

    public function key(): string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Active => 'active',
            self::Inactive => 'inactive',
            self::Blocked => 'blocked',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Blocked => 'Blocked',
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
            'active', 'approved' => self::Active,
            'inactive' => self::Inactive,
            'blocked', 'block' => self::Blocked,
            default => null,
        };
    }
}
