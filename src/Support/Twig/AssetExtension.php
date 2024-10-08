<?php

namespace Takemo101\CmsTool\Support\Twig;

use CmsTool\Theme\ThemeId;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Slim\Interfaces\RouteParserInterface;
use Takemo101\Chubby\Http\Uri\ApplicationUri;
use Takemo101\CmsTool\Http\Action\Theme\ActiveThemeAssetAction;
use Takemo101\CmsTool\Http\Action\ThemeAssetAction;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;

class AssetExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param RouteParserInterface $routeParser
     * @param ApplicationUri $uri
     * @param LocalPublicStoragePath $localPublicStoragePath
     */
    public function __construct(
        private readonly RouteParserInterface $routeParser,
        private readonly ApplicationUri $uri,
        private readonly LocalPublicStoragePath $localPublicStoragePath,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', $this->getAssetUrl(...)),
            new TwigFunction('theme', $this->getThemeAssetUrl(...)),
            new TwigFunction('vendor', $this->getVendorAssetUrl(...)),
            new TwigFunction('storage', $this->getLocalPublicStorageUrl(...)),
        ];
    }

    public function getAssetUrl(?string $path = null, bool $full = false): string
    {
        if (empty($path)) {
            return '';
        }

        $data = [
            'path' => $path,
        ];

        return $full
            ? $this->routeParser->fullUrlFor(
                $this->uri,
                ActiveThemeAssetAction::RouteName,
                $data,
            )
            : $this->routeParser->urlFor(
                ActiveThemeAssetAction::RouteName,
                $data,
            );
    }

    public function getThemeAssetUrl(string|ThemeId $id, ?string $path = null, bool $full = false): string
    {
        if (empty($path)) {
            return '';
        }

        $data = [
            'id' => (string) $id,
            'path' => $path,
        ];

        return $full
            ? $this->routeParser->fullUrlFor(
                $this->uri,
                ThemeAssetAction::RouteName,
                $data,
            )
            : $this->routeParser->urlFor(
                ThemeAssetAction::RouteName,
                $data,
            );
    }

    public function getVendorAssetUrl(?string $path = null, bool $full = false): string
    {
        if (empty($path)) {
            return '';
        }

        $data = [
            'path' => $path,
        ];

        return $full
            ? $this->routeParser->fullUrlFor(
                $this->uri,
                VendorAssetAction::RouteName,
                $data,
            )
            : $this->routeParser->urlFor(
                VendorAssetAction::RouteName,
                $data,
            );
    }

    public function getLocalPublicStorageUrl(?string $path = null, bool $full = false): string
    {
        if (empty($path)) {
            return '';
        }

        $storageUrl = $this->localPublicStoragePath->getUrl($path);

        if (!$full) {
            return $storageUrl;
        }

        return strpos($storageUrl, 'http') === 0
            ? $storageUrl
            : $this->uri->getBase() . $storageUrl;
    }
}
