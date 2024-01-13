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
        public ?string $link,
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

    /**
     * Create a new instance from an array of data.
     *
     * @param array{
     *  name:string,
     *  link?:?string,
     * } $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            link: $data['link'] ?? null,
        );
    }
}
