<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use DOMElement;

/**
 * Data class representing the header title of the content.
 *
 * @immutable
 */
readonly class HeaderTitle
{
    /**
     * @var integer[]
     */
    public const LevelRange = [1, 2, 3, 4, 5, 6];

    /**
     * constructor
     *
     * @param string $id
     * @param string $title
     * @param integer $level header level 1 to 6
     */
    public function __construct(
        public string $id,
        public string $title,
        public int $level,
    ) {
        assert(
            in_array($level, self::LevelRange),
            'Level is invalid',
        );
    }

    /**
     * Check if there is an ID
     *
     * @return boolean
     */
    public function hasId(): bool
    {
        return $this->id !== '';
    }

    /**
     * Check if there is a title
     *
     * @return boolean
     */
    public function hasTitle(): bool
    {
        return $this->title !== '';
    }

    /**
     * Create an instance from DOMElement
     *
     * @param DOMElement $element
     * @return self
     */
    public static function fromHTagDOMElement(DOMElement $element): self
    {
        return new self(
            $element->getAttribute('id'),
            $element->textContent,
            (int)str_replace('h', '', $element->tagName),
        );
    }
}
