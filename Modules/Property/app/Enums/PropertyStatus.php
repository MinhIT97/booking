<?php

namespace Modules\Property\Enums;

enum PropertyStatus: int
{
    case Active = 2;
    case Draft  = 1;

    /**
     * Human-readable label for display in UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Draft  => 'Draft',
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
