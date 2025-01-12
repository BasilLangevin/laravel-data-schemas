<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Declined;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\In;

class EnumKeyword extends Keyword
{
    /**
     * The enum values to apply when the given attribute is present.
     */
    const RULE_VALUES = [
        Accepted::class => [
            'string' => ['yes', 'on', '1', 'true'],
        ],
        BooleanType::class => [
            'boolean' => [true, false],
            'integer' => [0, 1],
            'string' => ['0', '1'],
        ],
        Declined::class => [
            'string' => ['no', 'off', '0', 'false'],
        ],
    ];

    public function __construct(protected string|array $value)
    {
        if (is_array($value)) {
            return;
        }

        if (! enum_exists($value)) {
            throw new \InvalidArgumentException("Enum '{$value}' is not a valid enum.");
        }

        $reflection = new \ReflectionEnum($value);

        if (! $reflection->isBacked()) {
            throw new \InvalidArgumentException("Enum '{$value}' is not a backed enum. Only backed enums are supported.");
        }
    }

    /**
     * Get the value of the keyword.
     */
    public function get(): string|array
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        $keywords = array_filter([
            'enum' => $this->getValues(),
            'x-enum-values' => $this->getValueDescriptions(),
        ]);

        return $schema->merge($keywords);
    }

    /**
     * Check if the value is an enum.
     */
    protected function isEnum(): bool
    {
        return is_string($this->get()) && enum_exists($this->get());
    }

    /**
     * Get the values for the keyword.
     */
    protected function getValues(): array
    {
        $values = $this->isEnum() ? $this->get()::cases() : $this->get();

        return collect($values)->map(function ($value) {
            if (! is_object($value)) {
                return $value;
            }

            if (property_exists($value, 'value')) {
                return $value->value;
            }

            throw new \Exception('Non-backed enum values are not supported.');
        })->toArray();
    }

    /**
     * Get the value descriptions for the enum if the enum backing type is an integer.
     */
    protected function getValueDescriptions(): ?array
    {
        if (! $this->isEnum()) {
            return null;
        }

        $reflection = new \ReflectionEnum($this->get());

        if ($reflection->getBackingType()->getName() !== 'int') {
            return null;
        }

        return collect($this->get()::cases())->mapWithKeys(function ($value) {
            return [$value->name => $value->value];
        })->toArray();
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): string|array
    {
        if ($property->hasAttribute(Enum::class)) {
            return static::parseEnumAttribute($property);
        }

        if ($property->hasAttribute(In::class)) {
            return static::parseInAttribute($property);
        }

        if (static::hasDefinedRule($property)) {
            return static::parseDefinedRule($property) ?? throw new KeywordValueCouldNotBeInferred;
        }

        throw new KeywordValueCouldNotBeInferred;
    }

    /**
     * Parse the Enum attribute.
     */
    protected static function parseEnumAttribute(ReflectionHelper $property): string|array
    {
        $enum = $property->getAttribute(Enum::class);
        $reflection = new \ReflectionObject($enum);

        return $reflection->getProperty('enum')->getValue($enum);
    }

    /**
     * Parse the In attribute.
     */
    protected static function parseInAttribute(ReflectionHelper $property): string|array
    {
        $in = $property->getAttribute(In::class);
        $reflection = new \ReflectionObject($in);

        return $reflection->getProperty('values')->getValue($in)[0];
    }

    /**
     * Get the first Rule attribute that is defined in the RULE_VALUES array.
     */
    protected static function getDefinedRule(ReflectionHelper $property): ?array
    {
        return collect(static::RULE_VALUES)
            ->first(fn ($v, $attribute) => $property->hasAttribute($attribute));
    }

    /**
     * Check if the property has an attribute that is defined in the RULE_VALUES array.
     */
    protected static function hasDefinedRule(ReflectionHelper $property): bool
    {
        return static::getDefinedRule($property) !== null;
    }

    /**
     * Parse the defined rule.
     */
    protected static function parseDefinedRule(ReflectionHelper $property): ?array
    {
        return collect(static::getDefinedRule($property))
            ->first(function ($v, $type) use ($property) {
                // User-defined methods are case insensitive, so this doesn't need mutation testing.
                $method = 'is'.ucfirst($type); // @pest-mutate-ignore

                return $property->{$method}();
            });
    }
}
