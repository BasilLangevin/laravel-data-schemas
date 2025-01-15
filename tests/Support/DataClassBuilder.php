<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Support;

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class DataClassBuilder
{
    protected Collection $properties;

    public function __construct()
    {
        $this->properties = collect();
    }

    public function addProperty(string $type, string $name, array $attributes = [], ?string $default = null): self
    {
        $this->properties->push(new Property($type, $name, $attributes, $default));

        return $this;
    }

    public function addArrayProperty(string $name, array $attributes = [], ?string $default = null): self
    {
        return $this->addProperty('array', $name, $attributes, $default);
    }

    public function addBooleanProperty(string $name, array $attributes = [], ?string $default = null): self
    {
        return $this->addProperty('bool', $name, $attributes, $default);
    }

    public function addIntegerProperty(string $name, array $attributes = [], ?string $default = null): self
    {
        return $this->addProperty('int', $name, $attributes, $default);
    }

    public function addNumberProperty(string $name, array $attributes = [], ?string $default = null): self
    {
        return $this->addProperty('float', $name, $attributes, $default);
    }

    public function addObjectProperty(string $name, array $attributes = [], ?string $default = null): self
    {
        return $this->addProperty('object', $name, $attributes, $default);
    }

    public function addStringProperty(string $name, array $attributes = [], ?string $default = null): self
    {
        return $this->addProperty('string', $name, $attributes, $default);
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
            ->replaceMatches("/[\(\)\\\',]/", '')
            ->studly()
            ->take(20)
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
