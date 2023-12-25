<?php

namespace Takemo101\CmsTool\Preset\Shared;

class LayeredTemplateNamesCreator
{
    /** @var string */
    public const TemplateNamePrefix = 'pages';

    /** @var string */
    public const IndexNameSuffix = '_index';

    /** @var string */
    public const DetailNameSuffix = '_detail';

    /**
     * Get an array of the template name of an index page or a single page
     *
     * @param string $signature
     * @return string[]
     */
    public function index(string $signature): array
    {
        $prefix = self::TemplateNamePrefix;
        $suffix = self::IndexNameSuffix;

        return [
            "{$prefix}.{$signature}.{$suffix}",
            "{$prefix}.{$signature}",
        ];
    }

    /**
     * Get the array of the template name on the details page
     *
     * @param string $signature
     * @param string $id
     * @return string[]
     */
    public function detail(string $signature, string $id): array
    {
        $prefix = self::TemplateNamePrefix;
        $suffix = self::DetailNameSuffix;

        return [
            "{$prefix}.{$signature}.{$id}.{$suffix}",
            "{$prefix}.{$signature}.{$id}",
            "{$prefix}.{$signature}.{$suffix}",
        ];
    }

    /**
     * Get the array of the template name on the details page
     *
     * @param string $signature
     * @param string $id
     * @return string[]
     */
    public function taxonomy(string $signature, string $id): array
    {
        $prefix = self::TemplateNamePrefix;
        $suffix = self::IndexNameSuffix;

        return [
            "{$prefix}.{$signature}.{$id}.{$suffix}",
            "{$prefix}.{$signature}.{$id}",
            "{$prefix}.{$signature}.{$suffix}",
            "{$prefix}.{$signature}",
        ];
    }
}
