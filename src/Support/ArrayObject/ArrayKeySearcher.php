<?php

namespace Takemo101\CmsTool\Support\ArrayObject;

use function Symfony\Component\String\u;

/**
 * Search for a converted key in the array.
 */
class ArrayKeySearcher
{
    /**
     * @var (callable(string):string)[]
     */
    private readonly array $transformers;

    /**
     * constructor
     *
     * @param (callable(string):string)[] ...$transformers
     */
    public function __construct(
        callable ...$transformers,
    ) {
        $this->transformers = $transformers;
    }

    /**
     * Search key in items
     *
     * @param string $key
     * @param array<string,mixed> $items
     * @return string|false
     */
    public function search(
        string $key,
        array $items,
    ): string|false {

        if (array_key_exists($key, $items)) {
            return $key;
        }

        foreach ($this->transformers as $transformer) {
            $key = $transformer($key);

            if (array_key_exists($key, $items)) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Create a camel case searcher.
     *
     * @return self
     */
    public static function createSnakeAndCamelCaseSearcher(): self
    {
        return new self(
            fn (string $key) => u($key)->camel()->toString(),
            fn (string $key) => u($key)->snake()->toString(),
        );
    }
}
