<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

covers(RequiredKeyword::class);

class RequiredKeywordTestSchema extends Schema
{
    public static array $keywords = [
        RequiredKeyword::class,
    ];
}

it('can set its required fields', function () {
    $schema = new RequiredKeywordTestSchema;
    $schema->required(['test3', 'test4']);

    expect($schema->getRequired())->toBe(['test3', 'test4']);
});

it('can apply the required fields to a schema', function () {
    $schema = new RequiredKeywordTestSchema;
    $schema->required(['test3', 'test4']);

    $data = collect([
        'type' => DataType::Object->value,
    ]);

    $result = $schema->applyKeyword(RequiredKeyword::class, $data);

    expect($result)->toEqual(collect([
        'type' => DataType::Object->value,
        'required' => ['test3', 'test4'],
    ]));
});
