<?php

namespace Takemo101\CmsTool\Support\Twig;

use CmsTool\Theme\ThemeId;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Slim\Interfaces\RouteParserInterface;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\CmsTool\Http\Action\Theme\ActiveThemeAssetAction;
use Takemo101\CmsTool\Http\Action\ThemeAssetAction;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;
use Takemo101\CmsTool\Support\Uri\ApplicationUrl;

class AssetExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param RouteParserInterface $routeParser
     * @param ApplicationUrl $appUrl
     * @param PathHelper $helper
     * @param LocalPublicStoragePath $localPublicStoragePath
     */
    public function __construct(
        private RouteParserInterface $routeParser,
        private ApplicationUrl $appUrl,
        private PathHelper $helper,
        private LocalPublicStoragePath $localPublicStoragePath,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [$this, 'getAssetUrl']),
            new TwigFunction('theme', [$this, 'getThemeAssetUrl']),
            new TwigFunction('vendor', [$this, 'getVendorAssetUrl']),
            new TwigFunction('storage', [$this, 'getLocalPublicStorageUrl']),
        ];
    }

    public function getAssetUrl(?string $path = null): string
    {
        if (empty($path)) {
            return '';
        }

        return $this->routeParser->fullUrlFor(
            $this->appUrl,
            ActiveThemeAssetAction::RouteName,
            [
                'path' => $path,
            ]
        );
    }

    public function getThemeAssetUrl(string|ThemeId $id, ?string $path = null): string
    {
        if (empty($path)) {
            return '';
        }

        return $this->routeParser->fullUrlFor(
            $this->appUrl,
            ThemeAssetAction::RouteName,
            [
                'id' => (string) $id,
                'path' => $path,
            ]
        );
    }

    public function getVendorAssetUrl(?string $path = null): string
    {
        if (empty($path)) {
            return '';
        }

        return $this->routeParser->fullUrlFor(
            $this->appUrl,
            VendorAssetAction::RouteName,
            [
                'path' => $path,
            ]
        );
    }

    public function getLocalPublicStorageUrl(?string $path = null): string
    {
        if (empty($path)) {
            return '';
        }

        $storageUrl = $this->localPublicStoragePath->getUrl($path);

        return strpos($storageUrl, 'http') === 0
            ? $storageUrl
            : $this->helper->join(
                $this->appUrl->__toString(),
                $storageUrl,
            );
    }
}
