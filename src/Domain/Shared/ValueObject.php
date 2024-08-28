<?php

namespace Takemo101\CmsTool\Domain\Shared;

/**
 * @template T
 */
interface ValueObject
{
    /**
     * @return T
     */
    public function value();

    /**
     * @param ValueObject<T> $other
     * @return boolean
     */
    public function equals(ValueObject $other): bool;
}
