<?php

use BasilLangevin\LaravelDataSchemas\Transformers\DocBlockParser;

covers(DocBlockParser::class);

test('the make method returns a DocBlockParser instance when given a doc block', function () {
    $parser = DocBlockParser::make('/** @var string $name */');

    expect($parser)->toBeInstanceOf(DocBlockParser::class);
});

test('the make method returns null when given an empty value', function () {
    expect(DocBlockParser::make(''))->toBeNull();
});

it('can get a param description', function () {
    $parser = DocBlockParser::make('/** @param string $name The name of the person */');

    expect($parser->getParamDescription('name'))->toBe('The name of the person');
});

test('getParamDescription returns null when the param is not found', function () {
    $parser = DocBlockParser::make('/** @param string $name The name of the person */');

    expect($parser->getParamDescription('age'))->toBeNull();
});

it('can get a var description', function () {
    $parser = DocBlockParser::make('/** @var string $name The name of the person */');

    expect($parser->getVarDescription('name'))->toBe('The name of the person');
});

test('getVarDescription returns null when the var is not found', function () {
    $parser = DocBlockParser::make('/** @var string $name The name of the person */');

    expect($parser->getVarDescription('age'))->toBeNull();
});

test('getVarDescription returns the first var tag when no name is provided', function () {
    $parser = DocBlockParser::make('/** @var string $name The name of the person */');

    expect($parser->getVarDescription())->toBe('The name of the person');
});

it('can get a doc block summary', function () {
    $parser = DocBlockParser::make('/** This is a test description. */');

    expect($parser->getSummary())->toBe('This is a test description.');
});

test('getSummary returns null when the doc block has no text nodes', function () {
    $parser = DocBlockParser::make('/** @param string $name The name of the person */');

    expect($parser->getSummary())->toBeNull();
});

test('getTextNodes returns an array of text nodes', function () {
    $parser = DocBlockParser::make(<<<'DOC'
        /**
         * First text node.
         *
         * Second text node.
         *
         * @var string $name The name of the person
         */
    DOC);

    $reflection = new ReflectionClass($parser);
    $method = $reflection->getMethod('getTextNodes');
    $method->setAccessible(true);

    expect($method->invoke($parser))->toHaveLength(2);
});
