<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\String;

use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\AlphaDash;
use Spatie\LaravelData\Attributes\Validation\AlphaNumeric;
use Spatie\LaravelData\Attributes\Validation\DoesntEndWith;
use Spatie\LaravelData\Attributes\Validation\DoesntStartWith;
use Spatie\LaravelData\Attributes\Validation\EndsWith;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Lowercase;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\StartsWith;
use Spatie\LaravelData\Attributes\Validation\Uppercase;

class PatternKeyword extends Keyword
{
    /**
     * The patterns that can be inferred from validation rules.
     *
     * When an Attribute's regex pattern needs to include information
     * from the Attribute's parameters, the value is the name of a
     * static method on this class that constructs the pattern.
     *
     * JSON Schema only supports a subset of the standard regex syntax,
     * so these patterns are written to cover the most common cases.
     */
    const RULE_PATTERNS = [
        Alpha::class => '/^[a-zA-Z]+$/',
        AlphaDash::class => '/^[a-zA-Z0-9_-]+$/',
        AlphaNumeric::class => '/^[a-zA-Z0-9]+$/',
        DoesntEndWith::class => 'parseDoesntEndWith',
        DoesntStartWith::class => 'parseDoesntStartWith',
        EndsWith::class => 'parseEndsWith',
        IntegerType::class => '/^[0-9]+$/',
        Lowercase::class => '/^[^A-Z]+$/',
        Numeric::class => '/^-?(\d+|\d*\.\d+)([eE][+-]?\d+)?$/',
        Regex::class => 'parseRegex',
        StartsWith::class => 'parseStartsWith',
        Uppercase::class => '/^[^a-z]+$/',
    ];

    public function __construct(protected string $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): string
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'pattern' => $this->value,
        ]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): string
    {
        $patterns = collect(self::RULE_PATTERNS)
            ->filter(fn (string $pattern, string $rule) => $property->hasAttribute($rule))
            ->map(function (string $pattern, string $rule) use ($property) {
                if (Str::startsWith($pattern, 'parse')) {
                    return self::{$pattern}($property->getAttribute($rule));
                }

                return $pattern;
            });

        if ($patterns->isEmpty()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $patterns->first();
    }

    /**
     * Escape the values for use in a regex pattern.
     */
    protected static function prepareRegexValues(array $values): string
    {
        return collect($values)
            ->map(fn (string $value) => preg_quote($value, '/'))
            ->join('|');
    }

    /**
     * Parse the doesnt end with rule.
     */
    protected static function parseDoesntEndWith(DoesntEndWith $rule): string
    {
        return '/^(?!.*('.self::prepareRegexValues($rule->parameters()[0]).')$).*$/';
    }

    /**
     * Parse the doesnt start with rule.
     */
    protected static function parseDoesntStartWith(DoesntStartWith $rule): string
    {
        return '/^(?!'.self::prepareRegexValues($rule->parameters()[0]).').*$/';
    }

    /**
     * Parse the ends with rule.
     */
    protected static function parseEndsWith(EndsWith $rule): string
    {
        return '/('.self::prepareRegexValues($rule->parameters()[0]).')$/';
    }

    /**
     * Parse the starts with rule.
     */
    protected static function parseStartsWith(StartsWith $rule): string
    {
        return '/^('.self::prepareRegexValues($rule->parameters()[0]).')/';
    }

    /**
     * Parse the regex pattern from the regex rule.
     */
    protected static function parseRegex(Regex $rule): string
    {
        return $rule->parameters()[0];
    }
}
