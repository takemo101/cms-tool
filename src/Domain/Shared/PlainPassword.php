<?php

namespace Takemo101\CmsTool\Domain\Shared;

use Stringable;
use Takemo101\CmsTool\Domain\Shared\Trait\ValueObjectEquatable;

/**
 * @implements ValueObject<string>
 */
readonly class PlainPassword implements ValueObject, Stringable
{
    use ValueObjectEquatable;

    public const MaxLength = 50;

    public const MinLenth = 3;

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private string $value,
    ) {
        $length = strlen($value);

        assert(
            $length >= self::MinLenth && $length <= self::MaxLength,
            sprintf('password length must be between %d and %d', self::MinLenth, self::MaxLength),
        );
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
