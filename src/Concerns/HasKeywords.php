<?php

namespace BasilLangevin\LaravelDataSchemas\Concerns;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordNotSetException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasKeywords
{
    /**
     * The keywords that are available for this schema type.
     */
    public static array $keywords;

    /**
     * The instances of each keyword that has been set.
     */
    private array $keywordInstances = [];

    /**
     * Get the keyword class that has the method matching the given name.
     */
    private function getKeywordByMethod(string $name): ?string
    {
        return Arr::first(static::$keywords, function ($keyword) use ($name) {
            return $keyword::method() === $name;
        });
    }

    /**
     * Check if the keyword method exists among the available keywords.
     */
    private function keywordMethodExists(string $name): bool
    {
        return $this->getKeywordByMethod($name) !== null;
    }

    /**
     * Remove the get prefix from the keyword name.
     */
    private function removeGetPrefix(string $name): string
    {
        $name = str($name)->after('get');

        return $name->substr(0, 1)->lower()
            ->append($name->substr(1));
    }

    /**
     * Check if the keyword getter method exists among the available keywords.
     */
    private function keywordGetterExists(string $name): bool
    {
        if (! Str::startsWith($name, 'get')) {
            return false;
        }

        if (! ctype_upper(Str::charAt($name, 3))) {
            return false;
        }

        $method = $this->removeGetPrefix($name);

        return $this->keywordMethodExists($method);
    }

    /**
     * Set the value for the appropriate keyword.
     */
    private function setKeyword(string $name, ...$arguments): self
    {
        $keyword = $this->getKeywordByMethod($name);

        $this->keywordInstances[$name] = new $keyword(...$arguments);

        return $this;
    }

    /**
     * Get the value for the appropriate keyword.
     */
    private function getKeyword(string $name): mixed
    {
        $name = $this->removeGetPrefix($name);

        if (! array_key_exists($name, $this->keywordInstances)) {
            throw new KeywordNotSetException("The keyword \"{$name}\" has not been set.");
        }

        return $this->keywordInstances[$name]->get();
    }

    /**
     * Allow keyword methods to be called on the schema type.
     */
    public function __call($name, $arguments)
    {
        if ($this->keywordMethodExists($name)) {
            return $this->setKeyword($name, ...$arguments);
        }

        if ($this->keywordGetterExists($name)) {
            return $this->getKeyword($name);
        }

        throw new \Exception("Method \"{$name}\" not found");
    }
}
