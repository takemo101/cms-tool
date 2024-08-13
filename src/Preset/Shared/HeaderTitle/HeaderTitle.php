<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use DOMElement;

/**
 * Data class representing the header title of the content.
 *
 * @immutable
 */
readonly class HeaderTitle extends AbstractHeaderTitle
{
    /**
     * constructor
     *
     * @param string $id
     * @param string $title
     * @param HeaderTitleLevel $level header level 1 to 6
     */
    public function __construct(
        string $id,
        string $title,
        public HeaderTitleLevel $level,
    ) {
        parent::__construct(
            id: $id,
            title: $title,
        );
    }

    /**
     * Create to HeaderLayer
     *
     * @return HeaderLayer
     */
    public function toLayered(): HeaderLayer
    {
        return new HeaderLayer(
            level: $this->level,
            header: new LayeredHeaderTitle(
                id: $this->id,
                title: $this->title,
            ),
        );
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
            HeaderTitleLevel::fromHTagString($element->tagName),
        );
    }
}
