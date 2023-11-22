<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\UseCase\Shared\QueryService\InstallSettingData;
use Takemo101\CmsTool\UseCase\Shared\QueryService\MicroCmsApiData;
use Takemo101\CmsTool\UseCase\Shared\QueryService\RootAdminData;
use Takemo101\CmsTool\UseCase\Shared\QueryService\SiteMetaData;

class JsonAccessInstallSettingDataMapper
{
    /**
     * @param array{
     *  installed?: boolean,
     *  api?: array{
     *   key: string,
     *   service_id: string,
     *  },
     *  root?: array{
     *   name: string,
     *  },
     *  meta?: array{
     *   name: string,
     *   title: string,
     *   description: string|null,
     *  },
     * } $data
     * @return InstallSettingData
     */
    public function toData(array $data): InstallSettingData
    {
        $installed = $data['installed'] ?? false;

        $api = $data['api'] ?? false;

        $root = $data['root'] ?? false;

        $meta = $data['meta'] ?? false;

        return new InstallSettingData(
            installed: $installed,
            api: $api ?
                new MicroCmsApiData(
                    key: $api['key'],
                    serviceId: $api['service_id'],
                )
                : null,
            root: $root
                ? new RootAdminData(
                    name: $root['name'],
                )
                : null,
            meta: $meta
                ? new SiteMetaData(
                    name: $meta['name'],
                    title: $meta['title'],
                    description: $meta['description'],
                )
                : null,
        );
    }
}
