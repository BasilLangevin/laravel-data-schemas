<?php

namespace BasilLangevin\LaravelDataSchemas\Tests;

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class Property
{
    public function __construct(
        public string $type,
        public string $name,
        public array $attributes,
        public ?string $default = null,
    ) {}

    protected function stringifyAttributes(): string
    {
        if (empty($this->attributes)) {
            return '';
        }

        $result = collect($this->attributes)
            ->map(fn ($arguments) => collect($arguments)->join(', '))
            ->map(fn ($arguments, $attribute) => "{$attribute}({$arguments})")
            ->implode(', ');

        return "#[{$result}]";
    }

    protected function stringifyDefault(): string
    {
        if (is_string($this->default)) {
            return "'".addslashes($this->default)."'";
        }

        if (is_array($this->default)) {
            return json_encode($this->default);
        }

        if (! is_null($this->default)) {
            return $this->default;
        }

        if (Str::contains($this->type, ['null', '?'])) {
            return 'null';
        }

        return '';
    }

    public function getDefinition(): string
    {
        $attributes = $this->stringifyAttributes();
        $default = $this->stringifyDefault();

        return Str::of("public {$this->type} \${$this->name}")
            ->when(! empty($attributes), fn ($result) => $result->prepend("{$attributes}\n"))
            ->when(! empty($default), fn ($result) => $result->append(" = {$default}"));
    }
}

class ClassBuilder
{
    protected Collection $properties;

    public function __construct()
    {
        $this->properties = collect();
    }

    public function addProperty(string $type, string $name, array $attributes, ?string $default = null): self
    {
        $this->properties->push(new Property($type, $name, $attributes, $default));

        return $this;
    }

    public function addStringProperty(string $name, array $attributes, ?string $default = null): self
    {
        return $this->addProperty('string', $name, $attributes, $default);
    }

    public function addNullableStringProperty(string $name, array $attributes, ?string $default = null): self
    {
        return $this->addProperty('string|null', $name, $attributes, $default);
    }

    public function addIntegerProperty(string $name, array $attributes, ?string $default = null): self
    {
        return $this->addProperty('int', $name, $attributes, $default);
    }

    public function addNullableIntegerProperty(string $name, array $attributes, ?string $default = null): self
    {
        return $this->addProperty('int|null', $name, $attributes, $default);
    }

    /**
     * Get a string of property definitions for the class builder.
     */
    protected function getPropertyDefinitions(): string
    {
        return $this->properties->map->getDefinition()->implode(",\n");
    }

    /**
     * Get the name of the test case that is currently being executed.
     */
    protected function getTestName(): string
    {
        return collect(debug_backtrace())
            ->filter(fn ($trace) => Arr::has($trace, 'file'))
            ->filter(fn ($trace) => Str::endsWith($trace['file'], 'TestCase.php'))
            ->pluck('object')
            ->first()
            ->getPrintableTestCaseMethodName();
    }

    /**
     * Create a unique name to use for creating a data class for the current test.
     */
    protected function getTestClassName(): string
    {
        return str($this->getTestName())
            ->studly()
            ->append('_')
            ->append(mt_rand(1000, 9999));
    }

    /**
     * Get the schema for the class builder.
     */
    public function getSchema(?string $propertyScope = null): array
    {
        $className = $this->getTestClassName();
        $extends = Data::class;
        $propertyDefinitions = $this->getPropertyDefinitions();

        eval(<<<EOT
        class {$className} extends {$extends}
        {
            public function __construct(
                {$propertyDefinitions}
            ) {}
        }
        EOT);

        $schema = TransformDataClassToSchema::run(ClassWrapper::make($className));

        $result = $schema->toArray();

        if (filled($propertyScope)) {
            $result = Arr::get($result, 'properties.'.$propertyScope);
        }

        return $result;
    }

    public function getProperties(): Collection
    {
        return $this->properties;
    }
}

trait TestsSchemaTransformation
{
    public function setupTestsSchemaTransformation(): void
    {
        $this->class = new ClassBuilder;

        expect()->extend('toHaveSchema', function (string|array $property, ?array $schema = null) {
            if (is_array($property)) {
                $schema = $property;
                $property = null;
            }

            return $this->getSchema($property)->toEqual($schema);
        });
    }
}
