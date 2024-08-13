<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

/**
 * Data class representing the header title of the content.
 *
 * @immutable
 */
abstract readonly class AbstractHeaderTitle
{
    /**
     * constructor
     *
     * @param string $id
     * @param string $title
     */
    public function __construct(
        public string $id,
        public string $title,
    ) {
        //
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
}
