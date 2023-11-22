<?php

namespace Takemo101\CmsTool\Domain\Shared;

use Stringable;

readonly class HashedPassword implements Stringable
{
    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        public string $value,
    ) {
        assert(!empty($value), 'value is empty');
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
