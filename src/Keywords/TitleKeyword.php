<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Transformers\DocBlockParser;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;

class TitleKeyword extends Keyword
{
    public function __construct(protected string $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'title' => $this->get(),
        ]);
    }

    /**
     * Infer the value of the keyword from the property, or return
     * null if the property schema should not have this keyword.
     */
    public static function parse(ReflectionHelper $reflector): ?string
    {
        if ($reflector->hasAttribute(Title::class)) {
            return $reflector->getAttribute(Title::class);
        }

        $docBlock = DocBlockParser::make($reflector->getDocComment());

        // The doc block summary only becomes the title if it also has a description.
        if (! $docBlock?->hasDescription()) {
            return null;
        }

        return $docBlock->getSummary() ?? null;
    }
}
