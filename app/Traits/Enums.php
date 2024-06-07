<?php

namespace App\Traits;

trait Enums
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
