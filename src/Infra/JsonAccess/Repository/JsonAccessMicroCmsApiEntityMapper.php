<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;

/**
 * @phpstan-type MicroCmsApiData = array{
 *   key: string,
 *   service_id: string,
 * }
 */
class JsonAccessMicroCmsApiEntityMapper
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
     * @param MicroCmsApi $api
     * @return array<string,mixed>
     */
    public function toArray(MicroCmsApi $api): array
    {
        /** @var array<string,mixed> */
        $serialized = $this->mapper->serializeObject($api);

        return $serialized;
    }

    /**
     * @param MicroCmsApiData $data
     * @return MicroCmsApi
     */
    public function toEntity(array $data): MicroCmsApi
    {
        return $this->mapper->hydrateObject(
            MicroCmsApi::class,
            $data,
        );
    }
}
