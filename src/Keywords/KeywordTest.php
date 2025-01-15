<?php

use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;

covers(Keyword::class);

function makeClass(string $name, ?string $method = null)
{
    $methodDefinition = $method ?
        "public static string \$method = '{$method}';"
        : '';

    eval("class {$name} extends \BasilLangevin\LaravelDataSchemas\Keywords\Keyword
    {
        {$methodDefinition}

        public function get(): mixed
        {
            return 'test';
        }

        public function apply(\Illuminate\Support\Collection \$schema): \Illuminate\Support\Collection
        {
            return \$schema;
        }
    }");

    return new $name;
}

it('generates a method name from the class name', function ($className, $method) {
    expect(makeClass($className)->method())->toBe($method);
})
    ->with([
        ['TestKeyword', 'test'],
        ['AnotherKeyword', 'another'],
        ['TheGeneratedKeyword', 'theGenerated'],
    ]);

it('can define a custom method name', function ($className, $method) {
    expect(makeClass($className, $method)->method())->toBe($method);
})
    ->with([
        ['TestCustomKeyword', 'randomMethod'],
        ['AnotherCustomKeyword', 'anotherMethod'],
        ['TheTestCustomKeyword', 'theMethod'],
    ]);
