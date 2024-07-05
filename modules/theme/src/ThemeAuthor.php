<?php

namespace CmsTool\Theme;

use Takemo101\Chubby\Contract\Arrayable;

/**
 * @implements Arrayable<string,mixed>
 */
readonly class ThemeAuthor implements Arrayable
{
    /**
     * constructor
     *
     * @param string $name
     * @param string|null $link
     */
    public function __construct(
        public string $name,
        public ?string $link = null,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'link' => $this->link,
        ];
    }
}
