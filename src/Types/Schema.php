<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Concerns\HasKeywords;

abstract class Schema implements \EchoLabs\Prism\Contracts\Schema
{
    use HasKeywords;

    public function __construct(
        protected string $name = '',
        string $description = '',
    ) {
        if ($description) {
            $this->description($description);
        }
    }

    /**
     * Get the name of the schema.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the schema.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
