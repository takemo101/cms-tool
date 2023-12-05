<?php

namespace Takemo101\CmsTool\Domain\Shared;

use Stringable;
use Takemo101\CmsTool\Domain\Shared\Trait\ValueObjectEquatable;

/**
 * @implements ValueObject<string>
 */
readonly class EmailAddress implements ValueObject, Stringable
{
    use ValueObjectEquatable;

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private string $value,
    ) {
        assert(!empty($value), 'value is empty');
        assert(filter_var($value, FILTER_VALIDATE_EMAIL), 'value is not email');
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
