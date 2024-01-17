<?php

use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Domain\Install\Installer;
use Takemo101\CmsTool\Domain\Install\InstallRepository;
use Takemo101\CmsTool\Domain\Install\Uninstaller;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessValidator;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiRepository;
use Takemo101\CmsTool\Domain\Publish\SitePublishRepository;
use Takemo101\CmsTool\Domain\Shared\PasswordHasher;
use Takemo101\CmsTool\Domain\SiteMeta\SiteMetaRepository;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeRepository;
use Takemo101\CmsTool\Domain\Tracking\TrackingCodeRepository;
use Takemo101\CmsTool\Domain\Webhook\WebhookTokenRepository;
use Takemo101\CmsTool\Infra\Hash\DefaultPasswordHasher;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessAdminAccountQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessInstallSettingQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessMicroCmsApiQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessSiteMetaQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\QueryService\JsonAccessTrackingCodeQueryService;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessActiveThemeRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessInstaller;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessInstallRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessMicroCmsApiRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessRootAdminRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessSiteMetaRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessSitePublishRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessTrackingCodeRepository;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessUninstaller;
use Takemo101\CmsTool\Infra\JsonAccess\Repository\JsonAccessWebhookTokenRepository;
use Takemo101\CmsTool\Infra\Saloon\QueryService\SaloonMicroCmsContentQueryService;
use Takemo101\CmsTool\Infra\Saloon\Validator\SaloonMicroCmsApiAccessValidator;
use Takemo101\CmsTool\Infra\Storage\Local\LocalSiteAssetStorage;
use Takemo101\CmsTool\UseCase\Admin\QueryService\AdminAccountQueryService;
use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingQueryService;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Api\MicroCmsApiQueryService;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;
use Takemo101\CmsTool\UseCase\Shared\Storage\SiteAssetStorage;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;
use Takemo101\CmsTool\UseCase\TrackingCode\QueryService\TrackingCodeQueryService;

use function DI\get;

return [
    // Repository
    MicroCmsApiRepository::class => get(JsonAccessMicroCmsApiRepository::class),
    RootAdminRepository::class => get(JsonAccessRootAdminRepository::class),
    SiteMetaRepository::class => get(JsonAccessSiteMetaRepository::class),
    InstallRepository::class => get(JsonAccessInstallRepository::class),
    SitePublishRepository::class => get(JsonAccessSitePublishRepository::class),
    ActiveThemeRepository::class => get(JsonAccessActiveThemeRepository::class),
    WebhookTokenRepository::class => get(JsonAccessWebhookTokenRepository::class),
    TrackingCodeRepository::class => get(JsonAccessTrackingCodeRepository::class),

    // QueryService
    InstallSettingQueryService::class => get(JsonAccessInstallSettingQueryService::class),
    AdminAccountQueryService::class => get(JsonAccessAdminAccountQueryService::class),
    MicroCmsApiQueryService::class => get(JsonAccessMicroCmsApiQueryService::class),
    SiteMetaQueryService::class => get(JsonAccessSiteMetaQueryService::class),
    MicroCmsContentQueryService::class => get(SaloonMicroCmsContentQueryService::class),
    TrackingCodeQueryService::class => get(JsonAccessTrackingCodeQueryService::class),

    // Other
    MicroCmsApiAccessValidator::class => get(SaloonMicroCmsApiAccessValidator::class),
    PasswordHasher::class => get(DefaultPasswordHasher::class),
    SiteAssetStorage::class => get(LocalSiteAssetStorage::class),

    Installer::class => get(JsonAccessInstaller::class),
    Uninstaller::class => get(JsonAccessUninstaller::class),
];
