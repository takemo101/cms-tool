<?php

namespace Takemo101\CmsTool\UseCase\Shared\Storage;

interface SiteAssetStorage extends Storage
{
    /**
     * Get the url for the site settings
     *
     * @param string $path Specify a relative path
     * @return string
     */
    public function url(string $path): string;
}
