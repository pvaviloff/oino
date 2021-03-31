<?php
namespace Oino\Helpers;

class InstanceHelper
{
    public static function isArrayInstanceOf(array $items, string $instance, bool $returnException = true): bool
    {
        foreach ($items as $item) {
            if (!is_a($item, $instance)) {
                if ($returnException) {
                    throw new \Exception("Array item must be instance of $instance");
                }

                return false;
            }
        }

        return true;
    }
}