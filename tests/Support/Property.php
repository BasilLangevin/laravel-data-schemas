<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
            ->mapWithKeys(function ($arguments, $attribute) {
                if (is_int($attribute)) {
                    return [$arguments => null];
                }

                return [$attribute => $arguments];
            })
            ->map(fn ($arguments) => $this->stringifyArguments($arguments))
            ->map(fn ($arguments, $attribute) => "{$attribute}({$arguments})")
            ->implode(', ');

        return "#[{$result}]";
    }

    protected function stringifyArguments(mixed $arguments): string
    {
        $arguments = Arr::wrap($arguments);
        $result = [];

        foreach ($arguments as $argument) {
            array_push($result, $this->stringifyArgument($argument));
        }

        return implode(', ', $result);
    }

    protected function stringifyArgument(mixed $argument): string
    {
        if ($argument instanceof Argument) {
            return $argument->getValue();
        }

        if (class_exists($argument)) {
            return $argument.'::class';
        }

        if (is_string($argument)) {
            return "'".addslashes($argument)."'";
        }

        return $argument;
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
