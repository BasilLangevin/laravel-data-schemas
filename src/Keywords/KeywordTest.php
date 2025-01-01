<?php

use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;

covers(Keyword::class);

function makeClass(string $name)
{
    eval("class {$name} extends \BasilLangevin\LaravelDataSchemas\Keywords\Keyword
    {
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

function makeClassWithCustomMethod(string $name, string $method)
{
    eval("class {$name} extends \BasilLangevin\LaravelDataSchemas\Keywords\Keyword
    {
        public static string \$method = '{$method}';

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
    $keyword = makeClass($className);

    expect($keyword->method())->toBe($method);
})
    ->with([
        ['TestKeyword', 'test'],
        ['AnotherKeyword', 'another'],
        ['TheGeneratedKeyword', 'theGenerated'],
    ]);

it('can define a custom method name', function ($className, $method) {
    $keyword = makeClassWithCustomMethod($className, $method);

    expect($keyword->method())->toBe($method);
})
    ->with([
        ['TestCustomKeyword', 'randomMethod'],
        ['AnotherCustomKeyword', 'anotherMethod'],
        ['TheTestCustomKeyword', 'theMethod'],
    ]);
