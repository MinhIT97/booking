<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseDTO
{
    /**
     * Seamlessly initialize DTO object footprint dynamically mapped from arrays or request payload data.
     */
    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $property => $value) {
            $camelProperty = Str::camel($property);

            // Directly populates matching class properties whether they were defined snake_case or camelCase
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            } elseif (property_exists($this, $camelProperty)) {
                $this->{$camelProperty} = $value;
            }
        }
    }

    /**
     * Construct the DTO from Laravel's active HTTP Request wrapper
     */
    abstract public static function fromRequest(Request $request): self;

    /**
     * Safely expose the mapped data footprint back into raw array structures (e.g. for database injection)
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
