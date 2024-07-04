<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\ThemeSchemaFactory;

class ThemeMetaFactory
{
    /**
     * @param ThemeSchemaFactory $factory
     */
    public function __construct(
        private readonly ThemeSchemaFactory $factory,
    ) {
        //
    }

    /**
     * Create theme meta object from data
     *
     * @param array{
     *   uid:string,
     *   name:string,
     *   version:string,
     *   images?:string[],
     *   tags?:string[],
     *   link?:?string,
     *   preset?:?string,
     *   author:array{
     *     name:string,
     *     link?:?string,
     *   }|string,
     *   readonly?:bool,
     *   extension?:array<string,mixed>,
     *   schema?:array<string,mixed>[],
     * } $data
     * @return ThemeMeta
     * @throws ArrayKeyMissingException
     */
    public function create(array $data): ThemeMeta
    {
        return new ThemeMeta(
            uid: $data['uid'] ?? ArrayKeyMissingException::throw('uid'),
            name: new ThemeName($data['name'] ?? ArrayKeyMissingException::throw('name')),
            version: $data['version'] ?? ArrayKeyMissingException::throw('version'),
            images: $data['images'] ?? [],
            tags: $data['tags'] ?? [],
            link: $data['link'] ?? null,
            preset: $data['preset'] ?? null,
            author: $this->createAuthor($data['author'] ?? ArrayKeyMissingException::throw('author')),
            readonly: $data['readonly'] ?? false,
            extension: $data['extension'] ?? [],
            schema: $this->factory->create($data['schema'] ?? []),
        );
    }

    /**
     * Create author object from data
     *
     * @param array{
     *   name:string,
     *   link?:?string,
     * }|string $data
     * @return ThemeAuthor
     * @throws ArrayKeyMissingException
     */
    private function createAuthor(string|array $data): ThemeAuthor
    {
        return is_string($data)
            ? new ThemeAuthor($data)
            : new ThemeAuthor(
                name: $data['name'] ?? ArrayKeyMissingException::throw('name'),
                link: $data['link'] ?? null,
            );
    }
}
