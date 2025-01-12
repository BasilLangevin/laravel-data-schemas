<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\ActiveUrl;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\DateEquals;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\IPv4;
use Spatie\LaravelData\Attributes\Validation\IPv6;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Attributes\Validation\Uuid;

class FormatKeyword extends Keyword
{
    /**
     * The formats that can be inferred from validation rules.
     */
    const RULE_FORMATS = [
        ActiveUrl::class => Format::Uri,
        After::class => Format::DateTime,
        AfterOrEqual::class => Format::DateTime,
        Before::class => Format::DateTime,
        BeforeOrEqual::class => Format::DateTime,
        Date::class => Format::DateTime,
        DateEquals::class => Format::DateTime,
        Email::class => Format::Email,
        IPv4::class => Format::IPv4,
        IPv6::class => Format::IPv6,
        Url::class => Format::Uri,
        Uuid::class => Format::Uuid,
    ];

    public function __construct(protected string|Format $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): string|Format
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'format' => $this->value instanceof Format
                ? $this->value->value
                : $this->value,
        ]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): string|Format
    {
        if (! $property->isString()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        $format = collect(self::RULE_FORMATS)
            ->filter(fn (Format $format, string $rule) => $property->hasAttribute($rule))
            ->first()?->value;

        return $format ?? throw new KeywordValueCouldNotBeInferred;
    }
}
