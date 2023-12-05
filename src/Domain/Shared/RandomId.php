<?php

namespace Takemo101\CmsTool\Domain\Shared;

use Stringable;

final class RandomId implements Stringable
{
    /**
     * constructor
     *
     * @param integer $length
     */
    public function __construct(
        private int $length = 16,
    ) {
        assert($length > 0, 'length must be greater than 0');
    }

    /**
     * Generate random character strings
     *
     * @return string
     */
    public function generate(): string
    {
        $result = '';

        while (strlen($result) < $this->length) {
            $result .= str_replace(
                ['/', '+', '='],
                '',
                base64_encode(
                    md5(uniqid()),
                ),
            );

            $result = substr(
                $result,
                0,
                $this->length,
            );
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->generate();
    }
}
