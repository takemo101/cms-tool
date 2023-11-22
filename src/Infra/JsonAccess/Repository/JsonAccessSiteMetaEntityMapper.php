<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;

final class JsonAccessSiteMetaEntityMapper
{
    /**
     * @param SiteMeta $meta
     * @return array<string,mixed>
     */
    public function toArray(SiteMeta $meta): array
    {
        return [
            'name' => $meta->name,
            'title' => $meta->title,
            'description' => $meta->description,
        ];
    }
}
