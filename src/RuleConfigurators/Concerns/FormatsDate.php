<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns;

use Carbon\Carbon;

trait FormatsDate
{
    protected static function formatDate(mixed $value): string
    {
        $value = new Carbon($value);

        if ($value->isUtc()) {
            return $value->toIso8601ZuluString();
        }

        return $value->toIso8601String();
    }
}
