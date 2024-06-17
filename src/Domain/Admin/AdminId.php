<?php

namespace Takemo101\CmsTool\Domain\Admin;

use Stringable;
use Takemo101\CmsTool\Domain\Shared\IdCreator;
use Takemo101\CmsTool\Domain\Shared\Trait\ValueObjectEquatable;
use Takemo101\CmsTool\Domain\Shared\ValueObject;

/**
 * @implements ValueObject<string>
 */
readonly class AdminId implements ValueObject, Stringable
{
    use ValueObjectEquatable;

    public const RootIdSymbol = 'root';

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private string $value,
    ) {
        assert(!empty($value), 'value is empty');
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Whether it is a root administrator
     *
     * @return boolean
     */
    public function isRoot(): bool
    {
        return self::RootIdSymbol === $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Create a root administrator id
     *
     * @return self
     */
    public static function root(): self
    {
        return new self(self::RootIdSymbol);
    }

    /**
     * Create a random administrator id
     *
     * @return self
     */
    public static function generate(): self
    {
        $id = IdCreator::ulid()->generate();

        if ($id === self::RootIdSymbol) {
            return self::generate();
        }

        return new self($id);
    }
}
