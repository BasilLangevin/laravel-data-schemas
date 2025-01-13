<?php

namespace BasilLangevin\LaravelDataSchemas\Actions\Concerns;

trait Runnable
{
    public static function run(...$arguments)
    {
        return app(self::class)->handle(...$arguments);
    }
}
