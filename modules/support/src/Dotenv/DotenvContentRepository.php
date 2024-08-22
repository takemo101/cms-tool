<?php

namespace CmsTool\Support\Dotenv;

interface DotenvContentRepository
{
    /**
     * Find the dotenv content.
     *
     * @return DotenvContent|null
     */
    public function find(): ?DotenvContent;

    /**
     * Save the dotenv content.
     *
     * @param DotenvContent $content
     * @return void
     */
    public function save(DotenvContent $content): void;
}
