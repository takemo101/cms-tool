<?php

namespace CmsTool\Theme;

readonly class ThemeAuthor
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
