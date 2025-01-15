<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns;

use Illuminate\Support\Carbon;

trait FormatsDate
{
    protected static function formatDate(mixed $value): string
    {
        if (! $value instanceof Carbon) {
            $value = new Carbon($value);
        }

        if ($value->isUtc()) {
            return $value->toIso8601ZuluString();
        }

        return $value->toIso8601String();
    }
}
