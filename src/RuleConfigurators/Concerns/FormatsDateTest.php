<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns\FormatsDate;
use Carbon\Carbon;

covers(FormatsDate::class);

class FormatsDateTestClass
{
    use FormatsDate;

    public function format(mixed $value): string
    {
        return self::formatDate($value);
    }
}

it('formats the date value correctly', function (mixed $argument, string $expected) {
    $class = new FormatsDateTestClass;

    expect($class->format($argument))->toBe($expected);
})->with([
    ['today', Carbon::parse('today')->toIso8601ZuluString()],
    [new Carbon('2025-01-15'), '2025-01-15T00:00:00Z'],
    [new Carbon('2025-01-15 12:00:00'), '2025-01-15T12:00:00Z'],
    [new Carbon('2025-01-15 12:00:00', 'America/New_York'), '2025-01-15T12:00:00-05:00'],
]);
