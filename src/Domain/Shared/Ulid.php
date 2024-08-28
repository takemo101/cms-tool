<?php

namespace Takemo101\CmsTool\Domain\Shared;

use Stringable;

class Ulid implements Stringable
{
    /**
     * @var integer
     */
    public const IDLength = 26;

    /**
     * @var string
     */
    private const Base32Symbols = '0123456789abcdefghjkmnpqrstvwxyz';

    /**
     * constructor
     *
     * @param integer $lastTimestamp
     */
    public function __construct(
        private int $lastTimestamp,
    ) {
        assert($lastTimestamp > 0, 'lastTimestamp must be greater than 0');
    }

    /**
     * Get the next time stamp
     *
     * @return integer
     */
    private function getNextTimestamp(): int
    {
        $current = self::getCurrentTimestamp();

        $lastTimestamp = $this->lastTimestamp;

        // Because it is bad if the final and the current time stamp are the same
        // In that case, increment and be the current time
        if ($current <= $lastTimestamp) {
            $lastTimestamp++;
            $current = $lastTimestamp;
        }

        // Record the final time stamp
        $this->lastTimestamp = $current;

        return $current;
    }

    /**
     * Generate a 10 -character timestamp string of ULID
     *
     * @return string
     */
    private function generateTimestampString(): string
    {
        $timestamp = $this->getNextTimestamp();

        $result = '';

        for ($i = 1; $i <= 10; $i++) {
            $result = self::Base32Symbols[$timestamp % 32] . $result;
            $timestamp = (int) floor($timestamp / 32);
        }

        return $result;
    }

    /**
     * Generate a random character string of 16 characters in the second half of ULID
     *
     * @return string
     */
    private function generateRandomString(): string
    {
        $result = '';

        for ($i = 1; $i <= 16; $i++) {
            $result .= self::Base32Symbols[random_int(0, 31)];
        }

        return $result;
    }

    /**
     * Generate ULID
     *
     * @return string
     */
    public function generate(): string
    {
        return $this->generateTimestampString() . $this->generateRandomString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->generate();
    }

    /**
     * Create a ULID instance from the current time stamp
     *
     * @return self
     */
    public static function createCurrent(): self
    {
        return new self(self::getCurrentTimestamp());
    }

    /**
     * Get the current time stamp
     *
     * @return integer
     */
    public static function getCurrentTimestamp(): int
    {
        return intval(microtime(true) * 1000);
    }
}
