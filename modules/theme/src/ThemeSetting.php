<?php

namespace CmsTool\Theme;

readonly class ThemeSetting
{
    /**
     * constructor
     *
     * @param string $uid
     * @param string $name
     * @param string $version
     * @param string $content
     * @param string[] $images
     * @param string[] $tags
     * @param string|null $link
     * @param ThemeAuthor $author
     */
    public function __construct(
        public string $uid,
        public string $name,
        public string $version,
        public string $content,
        public array $images,
        public array $tags,
        public ?string $link,
        public ThemeAuthor $author,
    ) {
        //
    }

    /**
     * Create a new instance from an array of data.
     *
     * @param array{
     *  uid:string,
     *  name:string,
     *  version:string,
     *  content:string,
     *  images?:string[],
     *  tags?:string[],
     *  link?:?string,
     *  author:array{
     *   name:string,
     *   link?:?string,
     *  }
     * } $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            uid: $data['uid'],
            name: $data['name'],
            version: $data['version'],
            content: $data['content'],
            images: $data['images'] ?? [],
            tags: $data['tags'] ?? [],
            link: $data['link'] ?? null,
            author: ThemeAuthor::fromArray($data['author']),
        );
    }
}
