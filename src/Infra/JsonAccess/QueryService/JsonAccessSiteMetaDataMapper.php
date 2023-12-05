<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\QueryService;

use EventSauce\ObjectHydrator\ObjectMapper;
use Takemo101\CmsTool\UseCase\Shared\Storage\SiteAssetStorage;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;
use Illuminate\Support\Arr;

class JsonAccessSiteMetaDataMapper
{
    /**
     * constructor
     *
     * @param ObjectMapper $mapper
     * @param SiteAssetStorage $storage
     */
    public function __construct(
        private ObjectMapper $mapper,
        private SiteAssetStorage $storage,
    ) {
        //
    }

    /**
     * @param array{seo:array{favicon:?string,icon:?string)} $data
     * @return SiteMetaData
     */
    public function toData(array $data): SiteMetaData
    {
        /** @var string|null */
        $favicon = Arr::get($data, 'seo.favicon');

        /** @var string|null */
        $icon = Arr::get($data, 'seo.icon');

        $data['seo'] = [
            ...$data['seo'],
            'favicon' => $favicon
                ? $this->storage->url($favicon)
                : null,
            'icon' => $icon
                ? $this->storage->url($icon)
                : null
        ];

        return $this->mapper->hydrateObject(
            SiteMetaData::class,
            $data,
        );
    }
}
