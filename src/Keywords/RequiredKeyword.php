<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;

class RequiredKeyword extends Keyword
{
    public function __construct(protected array $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): array
    {
        return $this->value;
    }

    /**
     * Apply the keyword to the schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'required' => $this->get(),
        ]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $reflector): array
    {
        $result = collect([
            ...self::notNullProperties($reflector),
            ...self::presentProperties($reflector),
            ...self::requiredProperties($reflector),
        ])->unique();

        return $result->isEmpty()
            ? throw new KeywordValueCouldNotBeInferred
            : $result->toArray();
    }

    /**
     * Get the properties that are not nullable.
     */
    protected static function notNullProperties(ReflectionHelper $reflector): array
    {
        return $reflector->properties()
            ->filter(function (ReflectionHelper $property) {
                $type = $property->getType();

                return $type !== null && ! $type->allowsNull();
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the properties that have the Present attribute.
     */
    protected static function presentProperties(ReflectionHelper $reflector): array
    {
        return $reflector->properties()
            ->filter(function (ReflectionHelper $property) {
                return $property->hasAttribute(Present::class);
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the properties that have the Required attribute.
     */
    protected static function requiredProperties(ReflectionHelper $reflector): array
    {
        return $reflector->properties()
            ->filter(function (ReflectionHelper $property) {
                return $property->hasAttribute(Required::class);
            })
            ->map->getName()
            ->toArray();
    }
}
