<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

class DescriptionKeyword extends Keyword
{
    public function __construct(protected string $value) {}

    public function get(): mixed
    {
        return $this->value;
    }
}
