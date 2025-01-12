<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use Illuminate\Support\Arr;
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
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

covers(FormatKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    it('can set its format')
        ->expect(StringSchema::make()->format(Format::DateTime))
        ->getFormat()->toBe(Format::DateTime);

    it('can get its format')
        ->expect(StringSchema::make()->format(Format::DateTime))
        ->getFormat()->toBe(Format::DateTime);

    it('can apply the format to a schema')
        ->expect(StringSchema::make()->format(Format::DateTime))
        ->applyKeyword(FormatKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'format' => Format::DateTime->value,
        ]));
});

describe('Property annotations', function () {
    it('is not set when the property is not a string', function () {
        class NotStringPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(NotStringPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.format'))->toBeFalse();
    });

    it('is set to the Uri format when the ActiveUrl attribute is present', function () {
        class ActiveUrlPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[ActiveUrl]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(ActiveUrlPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::Uri->value);
    });

    it('is set to the DateTime format when the After attribute is present', function () {
        class AfterPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[After('2025-01-01')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::DateTime->value);
    });

    it('is set to the DateTime format when the AfterOrEqual attribute is present', function () {
        class AfterOrEqualPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[AfterOrEqual('2025-01-01')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterOrEqualPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::DateTime->value);
    });

    it('is set to the DateTime format when the Before attribute is present', function () {
        class BeforePropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[Before('2025-01-01')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BeforePropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::DateTime->value);
    });

    it('is set to the DateTime format when the BeforeOrEqual attribute is present', function () {
        class BeforeOrEqualPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[BeforeOrEqual('2025-01-01')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BeforeOrEqualPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::DateTime->value);
    });

    it('is set to the DateTime format when the Date attribute is present', function () {
        class DatePropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[Date]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DatePropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::DateTime->value);
    });

    it('is set to the DateTime format when the DateEquals attribute is present', function () {
        class DateEqualsPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[DateEquals]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DateEqualsPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::DateTime->value);
    });

    it('is set to the Email format when the Email attribute is present', function () {
        class EmailPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[Email]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EmailPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::Email->value);
    });

    it('is set to the IPv4 format when the IPv4 attribute is present', function () {
        class IPv4PropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[IPv4]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(IPv4PropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::IPv4->value);
    });

    it('is set to the IPv6 format when the IPv6 attribute is present', function () {
        class IPv6PropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[IPv6]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(IPv6PropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::IPv6->value);
    });

    it('is set to the Uri format when the Url attribute is present', function () {
        class UrlPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[Url]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(UrlPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::Uri->value);
    });

    it('is set to the Uuid format when the Uuid attribute is present', function () {
        class UuidPropertyAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[Uuid]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(UuidPropertyAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))
            ->toBe(Format::Uuid->value);
    });

    it('is not set when no applicable attribute is present', function () {
        class NonApplicableAttributeFormatKeywordTest extends Data
        {
            public function __construct(
                #[Max(10)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(NonApplicableAttributeFormatKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.format'))->toBeFalse();
    });

    it('only has a single format when multiple attributes are present', function () {
        class MultipleAttributesFormatKeywordTest extends Data
        {
            public function __construct(
                #[After('2025-01-01'), Date]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultipleAttributesFormatKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.format'))->toBe(Format::DateTime->value);
    });
});
