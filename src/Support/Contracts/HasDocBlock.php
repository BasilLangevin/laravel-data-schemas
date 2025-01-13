<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Contracts;

use BasilLangevin\LaravelDataSchemas\Support\DocBlockParser;

interface HasDocBlock
{
    public function getDocBlock(): ?DocBlockParser;
}
