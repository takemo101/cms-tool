<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMeta;

/**
 * @phpstan-type SiteMetaData = array{
 *   name: string,
 *   seo: array{
 *     title: ?string,
 *     description: ?string,
 *     keywords: ?string,
 *     favicon: ?string,
 *     icon: ?string,
 *     robots: ?string,
 *   },
 * }
 */
class JsonAccessSiteMetaEntityMapper
{
    /**
     * constructor
     *
     * @param ObjectMapper $mapper
     */
    public function __construct(
        private ObjectMapper $mapper,
    ) {
        //
    }

    /**
     * @param SiteMeta $meta
     * @return array<string,mixed>
     */
    public function toArray(SiteMeta $meta): array
    {
        /** @var array<string,mixed> */
        $serialized = $this->mapper->serializeObject($meta);

        return $serialized;
    }

    /**
     * @param SiteMetaData $data
     * @return SiteMeta
     */
    public function toEntity(array $data): SiteMeta
    {
        return $this->mapper->hydrateObject(
            SiteMeta::class,
            $data,
        );
    }
}
