<?php

namespace BasilLangevin\LaravelDataSchemas\Commands;

use Illuminate\Console\Command;

class LaravelDataSchemasCommand extends Command
{
    public $signature = 'laravel-data-schemas';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
