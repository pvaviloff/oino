<?php
namespace Oino\Helpers;

class StringHelper
{
    public static function equals(string $firstValue, string $secondValue): bool
    {
        return strtolower(self::clean($firstValue)) ===
            strtolower(self::clean($secondValue));
    }

    public static function clean(string $value): string
    {
        return str_replace(' ', '', $value);
    }
}