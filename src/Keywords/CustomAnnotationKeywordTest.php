<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use Carbon\Carbon;
use Illuminate\Support\Arr;
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
use Spatie\LaravelData\Data;

covers(CustomAnnotationKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    it('can set a custom annotation')
        ->expect(StringSchema::make()->customAnnotation('test', 'value'))
        ->getCustomAnnotation()->toBe(['x-test' => 'value']);

    it('can set a custom annotation with an array')
        ->expect(StringSchema::make()->customAnnotation(['test' => 'value']))
        ->getCustomAnnotation()->toBe(['x-test' => 'value']);

    it('can set multiple custom annotations with an array')
        ->expect(StringSchema::make()->customAnnotation(['test' => 'value', 'test2' => 'value2']))
        ->getCustomAnnotation()->toBe(['x-test' => 'value', 'x-test2' => 'value2']);

    it('can get its custom annotation')
        ->expect(StringSchema::make()->customAnnotation('test', 'value'))
        ->getCustomAnnotation()->toBe(['x-test' => 'value']);

    it('can apply the custom annotation to a schema')
        ->expect(StringSchema::make()->customAnnotation('test', 'value'))
        ->applyKeyword(CustomAnnotationKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'x-test' => 'value',
        ]));

    it('can apply multiple custom annotations to a schema')
        ->expect(StringSchema::make()->customAnnotation(['test' => 'value', 'test2' => 'value2']))
        ->applyKeyword(CustomAnnotationKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'x-test' => 'value',
            'x-test2' => 'value2',
        ]));
});

describe('Date annotation variations', function () {
    it('sets the x-date-after annotation when the After attribute is set to a relative time string', function () {
        class AfterPropertyAttributeWithRelativeTimeStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After('tomorrow')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithRelativeTimeStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after tomorrow.');
    });

    it('sets the x-date-after annotation when the After attribute is set to a date string', function () {
        class AfterPropertyAttributeWithDateStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After('2025-01-01')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithDateStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025-01-01.');
    });

    it('sets the x-date-after annotation when the After attribute is set to a different property', function () {
        class AfterPropertyAttributeWithPropertyCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After('testParameter2')]
                public string $testParameter,
                public string $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithPropertyCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after the value of testParameter2.');
    });

    it('sets the x-date-after annotation when the after attribute is set to a year', function () {
        class AfterPropertyAttributeWithYearCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After('2025')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithYearCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025.');
    });

    it('sets the x-date-after annotation when the after attribute is set to a month', function () {
        class AfterPropertyAttributeWithMonthCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After('2025-01')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithMonthCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025-01.');
    });

    it('sets the x-date-after annotation when the After attribute is set to a date with no time', function () {
        class AfterPropertyAttributeWithDateCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After(new Carbon('2025-01-01'))]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithDateCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025-01-01.');
    });

    it('sets the x-date-after annotation when the After attribute is set to a datetime', function () {
        class AfterPropertyAttributeWithDateTimeCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After(new Carbon('2025-01-01 12:00:00'))]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithDateTimeCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025-01-01 12:00:00.');
    });

    it('sets the x-date-after annotation when the After attribute is set to a datetime not in UTC', function () {
        class AfterPropertyAttributeWithDateTimeInUtcCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After(new Carbon('2025-01-01 12:00:00', 'America/New_York'))]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithDateTimeInUtcCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025-01-01T12:00:00-05:00.');
    });

    it('sets the x-date-after annotation when the After attribute is set to a DateTime instance', function () {
        class AfterPropertyAttributeWithDateTimeInstanceCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After(new DateTime('2025-01-01 12:00:00'))]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithDateTimeInstanceCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after 2025-01-01 12:00:00.');
    });
});

describe('Property annotations', function () {
    it('sets the x-date-after annotation when the After attribute is set to a string', function () {
        class AfterPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[After('tomorrow')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after'))
            ->toBe('The value must be after tomorrow.');
    });

    it('sets the x-date-after-or-equal annotation when the AfterOrEqual attribute is set to a string', function () {
        class AfterOrEqualPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[AfterOrEqual('tomorrow')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AfterOrEqualPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-after-or-equal'))
            ->toBe('The value must be after or equal to tomorrow.');
    });

    it('sets the x-date-before annotation when the Before attribute is set to a string', function () {
        class BeforePropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Before('tomorrow')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BeforePropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-before'))
            ->toBe('The value must be before tomorrow.');
    });

    it('sets the x-date-before-or-equal annotation when the BeforeOrEqual attribute is set to a string', function () {
        class BeforeOrEqualPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[BeforeOrEqual('tomorrow')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BeforeOrEqualPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-before-or-equal'))
            ->toBe('The value must be before or equal to tomorrow.');
    });

    it('sets a custom annotation when the CustomAnnotation attribute is set to a string', function () {
        class CustomAnnotationPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[CustomAnnotation('test', 'value')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(CustomAnnotationPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-test'))
            ->toBe('value');
    });

    it('sets custom annotations when the CustomAnnotation attribute is set to an array with multiple annotations', function () {
        class CustomAnnotationPropertyAttributeWithClosureCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[CustomAnnotation(['test' => 'value', 'test2' => 'value2'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(CustomAnnotationPropertyAttributeWithClosureCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-test'))
            ->toBe('value');

        expect(Arr::get($schema, 'properties.testParameter.x-test2'))
            ->toBe('value2');
    });

    it('sets custom annotations when multiple CustomAnnotation attributes are set', function () {
        class CustomAnnotationPropertyAttributeWithMultipleCustomAnnotationAttributesTest extends Data
        {
            public function __construct(
                #[CustomAnnotation('test', 'value'), CustomAnnotation('test2', 'value2')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(CustomAnnotationPropertyAttributeWithMultipleCustomAnnotationAttributesTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-test'))
            ->toBe('value');

        expect(Arr::get($schema, 'properties.testParameter.x-test2'))
            ->toBe('value2');
    });

    it('sets the x-date-format annotation when the DateFormat attribute is set', function () {
        class DateFormatPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DateFormat('Y-m-d')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DateFormatPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-format'))
            ->toBe('The value must match the format "Y-m-d".');
    });

    it('sets the x-date-format annotation when the DateFormat attribute is set to an array', function () {
        class DateFormatPropertyAttributeWithArrayCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DateFormat(['Y-m-d', 'Y-m-d H:i:s'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DateFormatPropertyAttributeWithArrayCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-date-format'))
            ->toBe('The value must match the format "Y-m-d" or "Y-m-d H:i:s".');
    });

    it('sets the x-different-than annotation when the Different attribute is set', function () {
        class DifferentPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Different('testParameter2')]
                public string $testParameter,
                public string $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(DifferentPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-different-than'))
            ->toBe('The value must be different from the value of testParameter2.');
    });

    it('sets the x-digits annotation when the Digits attribute is set', function () {
        class DigitsPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Digits(10)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DigitsPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-digits'))
            ->toBe('The value must have 10 digits.');
    });

    it('sets the x-digits-between annotation when the DigitsBetween attribute is set', function () {
        class DigitsBetweenPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DigitsBetween(10, 20)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DigitsBetweenPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-digits-between'))
            ->toBe('The value must have between 10 and 20 digits.');
    });

    it('sets the x-distinct annotation when the Distinct attribute is set', function () {
        class DistinctPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Distinct]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DistinctPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-distinct'))
            ->toBe('The value of each testParameter must be unique.');
    });

    it('sets the x-doesnt-end-with annotation when the DoesntEndWith attribute is set', function () {
        class DoesntEndWithPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DoesntEndWith('test')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntEndWithPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-doesnt-end-with'))
            ->toBe('The value must not end with "test".');
    });

    it('sets the x-doesnt-end-with annotation when the DoesntEndWith attribute is set to an array', function () {
        class DoesntEndWithPropertyAttributeWithArrayCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DoesntEndWith(['test', 'test2'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntEndWithPropertyAttributeWithArrayCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-doesnt-end-with'))
            ->toBe('The value must not end with "test" or "test2".');
    });

    it('sets the x-doesnt-start-with annotation when the DoesntStartWith attribute is set', function () {
        class DoesntStartWithPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DoesntStartWith('test')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntStartWithPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-doesnt-start-with'))
            ->toBe('The value must not start with "test".');
    });

    it('sets the x-doesnt-start-with annotation when the DoesntStartWith attribute is set to an array', function () {
        class DoesntStartWithPropertyAttributeWithArrayCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[DoesntStartWith(['test', 'test2'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntStartWithPropertyAttributeWithArrayCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-doesnt-start-with'))
            ->toBe('The value must not start with "test" or "test2".');
    });

    it('sets the x-ends-with annotation when the EndsWith attribute is set', function () {
        class EndsWithPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[EndsWith('test')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EndsWithPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-ends-with'))
            ->toBe('The value must end with "test".');
    });

    it('sets the x-ends-with annotation when the EndsWith attribute is set to an array', function () {
        class EndsWithPropertyAttributeWithArrayCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[EndsWith(['test', 'test2'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EndsWithPropertyAttributeWithArrayCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-ends-with'))
            ->toBe('The value must end with "test" or "test2".');
    });

    it('sets the x-greater-than annotation on a string property when the GreaterThan attribute is set to another property', function () {
        class GreaterThanPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan('testParameter2')]
                public string $testParameter,
                public string $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-greater-than'))
            ->toBe('The value must have more characters than the value of testParameter2.');
    });

    it('sets the x-greater-than annotation on an integer property when the GreaterThan attribute is set to another property', function () {
        class GreaterThanPropertyAttributeWithIntegerCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan('testParameter2')]
                public int $testParameter,
                public int $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanPropertyAttributeWithIntegerCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-greater-than'))
            ->toBe('The value must be greater than the value of testParameter2.');
    });

    it('does not set the x-greater-than annotation when the GreaterThan attribute has an int value', function () {
        class GreaterThanPropertyAttributeWithIntCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan(10)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanPropertyAttributeWithIntCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.x-greater-than'))
            ->toBeFalse();
    });

    it('sets the x-greater-than-or-equal-to annotation on a string property when the GreaterThanOrEqualTo attribute is set to another property', function () {
        class GreaterThanOrEqualToPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo('testParameter2')]
                public string $testParameter,
                public string $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-greater-than-or-equal-to'))
            ->toBe('The value must have at least as many characters as the value of testParameter2.');
    });

    it('sets the x-greater-than-or-equal-to annotation on an integer property when the GreaterThanOrEqualTo attribute is set to another property', function () {
        class GreaterThanOrEqualToPropertyAttributeWithIntegerCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo('testParameter2')]
                public int $testParameter,
                public int $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToPropertyAttributeWithIntegerCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-greater-than-or-equal-to'))
            ->toBe('The value must be greater than or equal to the value of testParameter2.');
    });

    it('does not set the x-greater-than-or-equal-to annotation when the GreaterThanOrEqualTo attribute has an int value', function () {
        class GreaterThanOrEqualToPropertyAttributeWithIntCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo(10)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToPropertyAttributeWithIntCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.x-greater-than-or-equal-to'))
            ->toBeFalse();
    });

    it('sets the x-less-than annotation on a string property when the LessThan attribute is set to another property', function () {
        class LessThanPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[LessThan('testParameter2')]
                public string $testParameter,
                public string $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(LessThanPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-less-than'))
            ->toBe('The value must have fewer characters than the value of testParameter2.');
    });

    it('sets the x-less-than annotation on an integer property when the LessThan attribute is set to another property', function () {
        class LessThanPropertyAttributeWithIntegerCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[LessThan('testParameter2')]
                public int $testParameter,
                public int $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(LessThanPropertyAttributeWithIntegerCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-less-than'))
            ->toBe('The value must be less than the value of testParameter2.');
    });

    it('does not set the x-less-than annotation when the LessThan attribute has an int value', function () {
        class LessThanPropertyAttributeWithIntCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[LessThan(10)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanPropertyAttributeWithIntCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.x-less-than'))
            ->toBeFalse();
    });

    it('sets the x-less-than-or-equal-to annotation on a string property when the LessThanOrEqualTo attribute is set to another property', function () {
        class LessThanOrEqualToPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo('testParameter2')]
                public string $testParameter,
                public string $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-less-than-or-equal-to'))
            ->toBe('The value must have at most as many characters as the value of testParameter2.');
    });

    it('sets the x-less-than-or-equal-to annotation on an integer property when the LessThanOrEqualTo attribute is set to another property', function () {
        class LessThanOrEqualToPropertyAttributeWithIntegerCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo('testParameter2')]
                public int $testParameter,
                public int $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToPropertyAttributeWithIntegerCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-less-than-or-equal-to'))
            ->toBe('The value must be less than or equal to the value of testParameter2.');
    });

    it('does not set the x-less-than-or-equal-to annotation when the LessThanOrEqualTo attribute has an int value', function () {
        class LessThanOrEqualToPropertyAttributeWithIntCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo(10)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToPropertyAttributeWithIntCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.x-less-than-or-equal-to'))
            ->toBeFalse();
    });

    it('sets the x-ip-address annotation when the IP attribute is set', function () {
        class IPPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[IP]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(IPPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-ip-address'))
            ->toBe('The value must be an IP address.');
    });

    it('sets the x-json annotation when the Json attribute is set', function () {
        class JsonPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Json]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(JsonPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-json'))
            ->toBe('The value must be a valid JSON string.');
    });

    it('sets the x-lowercase annotation when the Lowercase attribute is set', function () {
        class LowercasePropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Lowercase]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LowercasePropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-lowercase'))
            ->toBe('The value must be lowercase.');
    });

    it('sets the x-mac-address annotation when the MacAddress attribute is set', function () {
        class MacAddressPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[MacAddress]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MacAddressPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-mac-address'))
            ->toBe('The value must be a MAC address.');
    });

    it('sets the x-starts-with annotation when the StartsWith attribute is set', function () {
        class StartsWithPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[StartsWith('test')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(StartsWithPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-starts-with'))
            ->toBe('The value must start with "test".');
    });

    it('sets the x-starts-with annotation when the StartsWith attribute is set to an array', function () {
        class StartsWithPropertyAttributeWithArrayCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[StartsWith(['test', 'test2'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(StartsWithPropertyAttributeWithArrayCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-starts-with'))
            ->toBe('The value must start with "test" or "test2".');
    });

    it('sets the x-timezone annotation when the Timezone attribute is set', function () {
        class TimezonePropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Timezone]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(TimezonePropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-timezone'))
            ->toBe('The value must be a timezone.');
    });

    it('sets the x-uppercase annotation when the Uppercase attribute is set', function () {
        class UppercasePropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Uppercase]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(UppercasePropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-uppercase'))
            ->toBe('The value must be uppercase.');
    });

    it('sets the x-ulid annotation when the Ulid attribute is set', function () {
        class UlidPropertyAttributeWithStringCustomAnnotationKeywordTest extends Data
        {
            public function __construct(
                #[Ulid]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(UlidPropertyAttributeWithStringCustomAnnotationKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.x-ulid'))
            ->toBe('The value must be a valid ULID.');
    });
});
