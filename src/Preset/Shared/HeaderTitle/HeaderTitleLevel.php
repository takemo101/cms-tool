<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use Stringable;

/**
 * ValueObject representing the hierarchical level of a header title
 *
 * @immutable
 */
readonly class HeaderTitleLevel implements Stringable
{
    /**
     * @var integer[]
     */
    public const Range = [1, 2, 3, 4, 5, 6];

    public const Top = 1;

    public const Bottom = 6;

    /**
     * constructor
     *
     * @param integer $level header level 1 to 6
     */
    public function __construct(
        public int $level
    ) {
        assert(
            in_array($level, self::Range),
            'Level is invalid',
        );
    }

    /**
     * Check if the level is the top level
     *
     * @return boolean
     */
    public function isTop(): bool
    {
        return $this->level === self::Top;
    }

    /**
     * Check if the level is the bottom level
     *
     * @return boolean
     */
    public function isBottom(): bool
    {
        return $this->level === self::Bottom;
    }

    /**
     * Check if the level is the same as the specified level
     *
     * @param self $level
     * @return boolean
     */
    public function equals(self $level): bool
    {
        return $this->level === $level->level;
    }

    /**
     * Check if the level is next to the specified level
     *
     * @param self $level
     * @return boolean
     */
    public function isNext(
        self $level,
    ): bool {
        return $this->next()->equals($level);
    }

    /**
     * Check if the level is within the specified range
     *
     * @param integer $start
     * @param integer $end
     * @return boolean
     */
    public function isWithinRange(
        int $start = 1,
        int $end = 6,
    ): bool {
        return $this->level >= $start && $this->level <= $end;
    }

    /**
     * Get the next level
     *
     * @return self
     */
    public function next(): self
    {
        return new self($this->level + 1);
    }

    /**
     * Get the value of the level
     *
     * @return integer
     */
    public function value(): int
    {
        return $this->level;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return (string)$this->level;
    }

    /**
     * Create an instance from a string of the tag name of the header
     *
     * @param string $tagName
     * @return self
     */
    public static function fromHTagString(string $tagName): self
    {
        return new self((int)str_replace('h', '', $tagName));
    }
}
