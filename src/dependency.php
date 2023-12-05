<?php

use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Install\InstallRepository;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Domain\Publish\SitePublishRepository;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\Infra\Guzzle\Validator\GuzzleMicroCmsApiAccessValidator;
use Takemo101\CmsTool\Infra\Hash\DefaultPasswordHasher;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessAdminAccountQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessInstallSettingQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessMicroCmsApiQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessSiteMetaQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessInstallRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessMicroCmsApiRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessRootAdminRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessSiteMetaRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessSitePublishRepository;
use Takemo101\CmsTool\Infra\Storage\Local\LocalSiteAssetStorage;
use Takemo101\CmsTool\UseCase\Admin\QueryService\AdminAccountQueryService;
use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingQueryService;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\MicroCmsApiQueryService;
use Takemo101\CmsTool\UseCase\Shared\Storage\SiteAssetStorage;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

use function DI\get;

return [
    // Repository
    MicroCmsApiRepository::class => get(JsonAccessMicroCmsApiRepository::class),
    RootAdminRepository::class => get(JsonAccessRootAdminRepository::class),
    SiteMetaRepository::class => get(JsonAccessSiteMetaRepository::class),
    InstallRepository::class => get(JsonAccessInstallRepository::class),
    SitePublishRepository::class => get(JsonAccessSitePublishRepository::class),

    // QueryService
    InstallSettingQueryService::class => get(JsonAccessInstallSettingQueryService::class),
    AdminAccountQueryService::class => get(JsonAccessAdminAccountQueryService::class),
    MicroCmsApiQueryService::class => get(JsonAccessMicroCmsApiQueryService::class),
    SiteMetaQueryService::class => get(JsonAccessSiteMetaQueryService::class),

    // Other
    MicroCmsApiAccessValidator::class => get(GuzzleMicroCmsApiAccessValidator::class),
    PasswordHasher::class => get(DefaultPasswordHasher::class),
    SiteAssetStorage::class => get(LocalSiteAssetStorage::class),
];
