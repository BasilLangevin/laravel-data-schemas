<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Different;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Attributes\Validation\Distinct;
use Spatie\LaravelData\Attributes\Validation\DoesntEndWith;
use Spatie\LaravelData\Attributes\Validation\DoesntStartWith;
use Spatie\LaravelData\Attributes\Validation\EndsWith;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\IP;
use Spatie\LaravelData\Attributes\Validation\Json;
use Spatie\LaravelData\Attributes\Validation\LessThan;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Lowercase;
use Spatie\LaravelData\Attributes\Validation\MacAddress;
use Spatie\LaravelData\Attributes\Validation\StartsWith;
use Spatie\LaravelData\Attributes\Validation\Timezone;
use Spatie\LaravelData\Attributes\Validation\Ulid;
use Spatie\LaravelData\Attributes\Validation\Uppercase;
use Spatie\LaravelData\Support\Validation\References\FieldReference;

class CustomAnnotationKeyword extends Keyword
{
    /**
     * The custom annotations to apply when the given attribute is present.
     */
    const RULE_ANNOTATIONS = [
        After::class => 'parseAfterAttribute',
        AfterOrEqual::class => 'parseAfterOrEqualAttribute',
        Before::class => 'parseBeforeAttribute',
        BeforeOrEqual::class => 'parseBeforeOrEqualAttribute',
        CustomAnnotation::class => 'parseCustomAnnotationAttribute',
        DateFormat::class => 'parseDateFormatAttribute',
        Different::class => 'parseDifferentAttribute',
        Digits::class => 'parseDigitsAttribute',
        DigitsBetween::class => 'parseDigitsBetweenAttribute',
        Distinct::class => 'parseDistinctAttribute',
        DoesntEndWith::class => 'parseDoesntEndWithAttribute',
        DoesntStartWith::class => 'parseDoesntStartWithAttribute',
        EndsWith::class => 'parseEndsWithAttribute',
        GreaterThan::class => 'parseGreaterThanAttribute',
        GreaterThanOrEqualTo::class => 'parseGreaterThanOrEqualToAttribute',
        IP::class => ['x-ip-address' => 'The value must be an IP address.'],
        Json::class => ['x-json' => 'The value must be a valid JSON string.'],
        LessThan::class => 'parseLessThanAttribute',
        LessThanOrEqualTo::class => 'parseLessThanOrEqualToAttribute',
        Lowercase::class => ['x-lowercase' => 'The value must be lowercase.'],
        MacAddress::class => ['x-mac-address' => 'The value must be a MAC address.'],
        StartsWith::class => 'parseStartsWithAttribute',
        Timezone::class => ['x-timezone' => 'The value must be a timezone.'],
        Uppercase::class => ['x-uppercase' => 'The value must be uppercase.'],
        Ulid::class => ['x-ulid' => 'The value must be a valid ULID.'],
    ];

    public function __construct(protected string|array $annotation, protected ?string $value = null) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): array
    {
        $annotations = is_array($this->annotation)
            ? $this->annotation
            : [$this->annotation => $this->value];

        return collect($annotations)
            ->mapWithKeys(fn ($value, $key) => [Str::start($key, 'x-') => $value])
            ->toArray();
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge($this->get());
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): array
    {
        $annotations = collect(static::RULE_ANNOTATIONS)
            ->keys()
            ->filter(fn ($attribute) => $property->hasAttribute($attribute))
            ->flatMap(fn ($attribute) => static::parseAttribute($property, $attribute))
            ->filter();

        if ($annotations->isEmpty()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $annotations->toArray();
    }

    /**
     * Parse a property annotation for the given Rule attribute.
     */
    protected static function parseAttribute(ReflectionHelper $property, string $attribute): ?array
    {
        if (is_array(static::RULE_ANNOTATIONS[$attribute])) {
            return static::RULE_ANNOTATIONS[$attribute];
        }

        $method = static::RULE_ANNOTATIONS[$attribute];

        return static::$method($property);
    }

    /**
     * Parse the date string for the annotation for a date rule attribute.
     */
    protected static function parseDateString(ReflectionHelper $property, mixed $value): string
    {
        if ($property->siblingNames()->contains($value)) {
            return 'the value of '.$value;
        }

        if (is_string($value)) {
            return $value;
        }

        if (! $value instanceof Carbon) {
            $value = new Carbon($value);
        }

        return match (true) {
            $value->toAtomString() === $value->clone()->startOfDay()->toAtomString() => $value->format('Y-m-d'),
            $value->toAtomString() === $value->clone()->startOfMonth()->toAtomString() => $value->format('Y-m'),
            $value->toAtomString() === $value->clone()->startOfYear()->toAtomString() => $value->format('Y'),
            $value->isUtc() => $value->format('Y-m-d H:i:s'),
            default => $value->toAtomString(),
        };
    }

    /**
     * Parse the After attribute.
     */
    protected static function parseAfterAttribute(ReflectionHelper $property): array
    {
        $dateString = static::parseDateString(
            $property,
            $property->getAttribute(After::class)->parameters()[0]
        );

        return ['x-date-after' => 'The value must be after '.$dateString.'.'];
    }

    /**
     * Parse the AfterOrEqual attribute.
     */
    protected static function parseAfterOrEqualAttribute(ReflectionHelper $property): array
    {
        $dateString = static::parseDateString(
            $property,
            $property->getAttribute(AfterOrEqual::class)->parameters()[0]
        );

        return ['x-date-after-or-equal' => 'The value must be after or equal to '.$dateString.'.'];
    }

    /**
     * Parse the Before attribute.
     */
    protected static function parseBeforeAttribute(ReflectionHelper $property): array
    {
        $dateString = static::parseDateString(
            $property,
            $property->getAttribute(Before::class)->parameters()[0]
        );

        return ['x-date-before' => 'The value must be before '.$dateString.'.'];
    }

    /**
     * Parse the BeforeOrEqual attribute.
     */
    protected static function parseBeforeOrEqualAttribute(ReflectionHelper $property): array
    {
        $dateString = static::parseDateString(
            $property,
            $property->getAttribute(BeforeOrEqual::class)->parameters()[0]
        );

        return ['x-date-before-or-equal' => 'The value must be before or equal to '.$dateString.'.'];
    }

    /**
     * Parse the CustomAnnotation attribute.
     */
    protected static function parseCustomAnnotationAttribute(ReflectionHelper $property): array
    {
        $attributes = Collection::wrap($property->getAttribute(CustomAnnotation::class));

        return $attributes
            ->flatMap(fn ($attribute) => $attribute->getCustomAnnotation())
            ->toArray();
    }

    /**
     * Parse the DateFormat attribute.
     */
    protected static function parseDateFormatAttribute(ReflectionHelper $property): array
    {
        $format = collect($property->getAttribute(DateFormat::class)->parameters()[0])
            ->map(fn ($format) => '"'.$format.'"')
            ->join(', ', ' or ');

        return ['x-date-format' => 'The value must match the format '.$format.'.'];
    }

    /**
     * Parse the Different attribute.
     */
    protected static function parseDifferentAttribute(ReflectionHelper $property): array
    {
        $value = $property->getAttribute(Different::class)->parameters()[0];

        if ($value instanceof FieldReference) {
            $value = $value->name;
        }

        return ['x-different-than' => 'The value must be different from the value of '.$value.'.'];
    }

    /**
     * Parse the Digits attribute.
     */
    protected static function parseDigitsAttribute(ReflectionHelper $property): array
    {
        $digits = $property->getAttribute(Digits::class)->parameters()[0];

        return ['x-digits' => 'The value must have '.$digits.' digits.'];
    }

    /**
     * Parse the DigitsBetween attribute.
     */
    protected static function parseDigitsBetweenAttribute(ReflectionHelper $property): array
    {
        [$min, $max] = $property->getAttribute(DigitsBetween::class)->parameters();

        return ['x-digits-between' => 'The value must have between '.$min.' and '.$max.' digits.'];
    }

    /**
     * Parse the Distinct attribute.
     */
    protected static function parseDistinctAttribute(ReflectionHelper $property): array
    {
        return ['x-distinct' => 'The value of each '.$property->getName().' must be unique.'];
    }

    /**
     * Parse the DoesntEndWith attribute.
     */
    protected static function parseDoesntEndWithAttribute(ReflectionHelper $property): array
    {
        $values = collect($property->getAttribute(DoesntEndWith::class)->parameters()[0])
            ->map(fn ($value) => '"'.$value.'"')
            ->join(', ', ' or ');

        return ['x-doesnt-end-with' => 'The value must not end with '.$values.'.'];
    }

    /**
     * Parse the DoesntStartWith attribute.
     */
    protected static function parseDoesntStartWithAttribute(ReflectionHelper $property): array
    {
        $values = collect($property->getAttribute(DoesntStartWith::class)->parameters()[0])
            ->map(fn ($value) => '"'.$value.'"')
            ->join(', ', ' or ');

        return ['x-doesnt-start-with' => 'The value must not start with '.$values.'.'];
    }

    /**
     * Parse the EndsWith attribute.
     */
    protected static function parseEndsWithAttribute(ReflectionHelper $property): array
    {
        $values = collect($property->getAttribute(EndsWith::class)->parameters()[0])
            ->map(fn ($value) => '"'.$value.'"')
            ->join(', ', ' or ');

        return ['x-ends-with' => 'The value must end with '.$values.'.'];
    }

    /**
     * Parse the GreaterThan attribute.
     */
    protected static function parseGreaterThanAttribute(ReflectionHelper $property): ?array
    {
        $value = $property->getAttribute(GreaterThan::class)->parameters()[0];

        if (is_int($value)) {
            return null;
        }

        if ($value instanceof FieldReference) {
            $value = $value->name;
        }

        return match (true) {
            $property->isString() => ['x-greater-than' => 'The value must have more characters than the value of '.$value.'.'],
            $property->isInteger() => ['x-greater-than' => 'The value must be greater than the value of '.$value.'.'],
        };
    }

    /**
     * Parse the GreaterThanOrEqualTo attribute.
     */
    protected static function parseGreaterThanOrEqualToAttribute(ReflectionHelper $property): ?array
    {
        $value = $property->getAttribute(GreaterThanOrEqualTo::class)->parameters()[0];

        if (is_int($value)) {
            return null;
        }

        if ($value instanceof FieldReference) {
            $value = $value->name;
        }

        return match (true) {
            $property->isString() => ['x-greater-than-or-equal-to' => 'The value must have at least as many characters as the value of '.$value.'.'],
            $property->isInteger() => ['x-greater-than-or-equal-to' => 'The value must be greater than or equal to the value of '.$value.'.'],
        };
    }

    /**
     * Parse the LessThan attribute.
     */
    protected static function parseLessThanAttribute(ReflectionHelper $property): ?array
    {
        $value = $property->getAttribute(LessThan::class)->parameters()[0];

        if (is_int($value)) {
            return null;
        }

        if ($value instanceof FieldReference) {
            $value = $value->name;
        }

        return match (true) {
            $property->isString() => ['x-less-than' => 'The value must have fewer characters than the value of '.$value.'.'],
            $property->isInteger() => ['x-less-than' => 'The value must be less than the value of '.$value.'.'],
        };
    }

    /**
     * Parse the LessThanOrEqualTo attribute.
     */
    protected static function parseLessThanOrEqualToAttribute(ReflectionHelper $property): ?array
    {
        $value = $property->getAttribute(LessThanOrEqualTo::class)->parameters()[0];

        if (is_int($value)) {
            return null;
        }

        if ($value instanceof FieldReference) {
            $value = $value->name;
        }

        return match (true) {
            $property->isString() => ['x-less-than-or-equal-to' => 'The value must have at most as many characters as the value of '.$value.'.'],
            $property->isInteger() => ['x-less-than-or-equal-to' => 'The value must be less than or equal to the value of '.$value.'.'],
        };
    }

    /**
     * Parse the StartsWith attribute.
     */
    protected static function parseStartsWithAttribute(ReflectionHelper $property): array
    {
        $values = collect($property->getAttribute(StartsWith::class)->parameters()[0])
            ->map(fn ($value) => '"'.$value.'"')
            ->join(', ', ' or ');

        return ['x-starts-with' => 'The value must start with '.$values.'.'];
    }
}
