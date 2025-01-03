<?php

use BasilLangevin\LaravelDataSchemas\Transformers\DocBlockParser;

covers(DocBlockParser::class);

test('the make method returns a DocBlockParser instance when given a doc block')
    ->expect(DocBlockParser::make('/** @var string $name */'))
    ->toBeInstanceOf(DocBlockParser::class);

test('the make method returns null when given an empty value')
    ->expect(DocBlockParser::make(''))
    ->toBeNull();

it('can get a param description')
    ->expect(DocBlockParser::make('/** @param string $name The name of the person */'))
    ->getParamDescription('name')
    ->toBe('The name of the person');

test('getParamDescription returns null when the param is not found')
    ->expect(DocBlockParser::make('/** @param string $name The name of the person */'))
    ->getParamDescription('age')
    ->toBeNull();

it('can get a var description')
    ->expect(DocBlockParser::make('/** @var string $name The name of the person */'))
    ->getVarDescription('name')
    ->toBe('The name of the person');

test('getVarDescription returns null when the var is not found')
    ->expect(DocBlockParser::make('/** @var string $name The name of the person */'))
    ->getVarDescription('age')
    ->toBeNull();

test('getVarDescription returns the first var tag when no name is provided')
    ->expect(DocBlockParser::make('/** @var string $name The name of the person */'))
    ->getVarDescription()
    ->toBe('The name of the person');

it('can get a doc block summary')
    ->expect(DocBlockParser::make('/** This is a test description. */'))
    ->getSummary()
    ->toBe('This is a test description.');

test('getSummary returns null when the doc block has no text nodes')
    ->expect(DocBlockParser::make('/** @param string $name The name of the person */'))
    ->getSummary()
    ->toBeNull();

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
