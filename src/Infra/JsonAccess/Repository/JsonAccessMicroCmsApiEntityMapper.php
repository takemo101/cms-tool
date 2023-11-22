<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Illuminate\Support\Arr;

final class JsonAccessMicroCmsApiEntityMapper
{
    /**
     * @param MicroCmsApi $api
     * @return array<string,mixed>
     */
    public function toArray(MicroCmsApi $api): array
    {
        return [
            'key' => $api->key,
            'service_id' => $api->serviceId,
        ];
    }

    /**
     * @param array{
     *  key: string,
     *  service_id: string,
     * } $data
     * @return MicroCmsApi
     */
    public function toEntity(array $data): MicroCmsApi
    {
        return new MicroCmsApi(
            key: $data['key'],
            serviceId: $data['service_id'],
        );
    }
}
