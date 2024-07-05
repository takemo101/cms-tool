<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Schema\ThemeSchema;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * @implements Arrayable<string,mixed>
 */
readonly class ThemeMeta implements Arrayable
{
    /**
     * constructor
     *
     * @param string $uid
     * @param ThemeName $name
     * @param string $version
     * @param string[] $images
     * @param string[] $tags
     * @param string|null $link
     * @param string|null $preset
     * @param ThemeAuthor $author
     * @param bool $readonly
     * @param array<string,mixed> $extension
     * @param ThemeSchema $schema
     */
    public function __construct(
        public string $uid,
        public ThemeName $name,
        public string $version,
        public array $images,
        public array $tags,
        public ?string $link,
        public ?string $preset,
        public ThemeAuthor $author,
        public bool $readonly = false,
        public array $extension = [],
        public ThemeSchema $schema = new ThemeSchema(),
    ) {
        //
    }

    /**
     * Get the value of thumbnail
     */
    public function thumbnail(): ?string
    {
        return $this->images[0] ?? null;
    }

    /**
     * Duplicate metadata of the theme for theme copy
     *
     * @return self
     */
    public function copy(): self
    {
        return new self(
            uid: $this->uid,
            name: $this->name->copy(),
            version: $this->version,
            images: $this->images,
            tags: $this->tags,
            link: $this->link,
            preset: $this->preset,
            author: $this->author,
            readonly: false, // copy is not readonly
            extension: $this->extension,
            schema: $this->schema,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'uid' => $this->uid,
            'name' => $this->name->value(),
            'version' => $this->version,
            'images' => $this->images,
            'tags' => $this->tags,
            'link' => $this->link,
            'preset' => $this->preset,
            'author' => $this->author->toArray(),
            'readonly' => $this->readonly,
            'extension' => $this->extension,
            'schema' => $this->schema->toArray(),
        ];
    }
}
