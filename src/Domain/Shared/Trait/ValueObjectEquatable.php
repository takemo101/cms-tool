<?php

namespace Takemo101\CmsTool\Domain\Shared\Trait;

use Takemo101\CmsTool\Domain\Shared\ValueObject;

/**
 * @mixin ValueObject
 */
trait ValueObjectEquatable
{
    /**
     * @param object $other
     * @return boolean
     */
    public function equals(object $other): bool
    {
        return $other instanceof static && $this->value() === $other->value();
    }
}
