<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

trait ParsesDateString
{
    use FormatsDate;

    protected static function parseDateString(PropertyWrapper $property, mixed $value): string
    {
        if ($property->siblingNames()->contains($value)) {
            return 'the value of '.$value;
        }

        return self::formatDate($value);
    }
}
