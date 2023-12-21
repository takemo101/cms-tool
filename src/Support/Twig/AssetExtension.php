<?php

namespace Takemo101\CmsTool\Support\Twig;

use CmsTool\Theme\ThemeId;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Slim\Interfaces\RouteParserInterface;
use Takemo101\CmsTool\Http\Action\Theme\AssetAction;
use Takemo101\CmsTool\Http\Action\ThemeAssetAction;
use Takemo101\CmsTool\Http\Action\VendorAssetAction;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;

class AssetExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param RouteParserInterface $routeParser
     * @param LocalPublicStoragePath $localPublicStoragePath
     */
    public function __construct(
        private RouteParserInterface $routeParser,
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

    public function getAssetUrl(string $path = ''): string
    {
        return $this->routeParser->urlFor(
            AssetAction::RouteName,
            [
                'path' => $path,
            ]
        );
    }

    public function getThemeAssetUrl(string|ThemeId $id, string $path = ''): string
    {
        return $this->routeParser->urlFor(
            ThemeAssetAction::RouteName,
            [
                'id' => (string) $id,
                'path' => $path,
            ]
        );
    }

    public function getVendorAssetUrl(string $path = ''): string
    {
        return $this->routeParser->urlFor(
            VendorAssetAction::RouteName,
            [
                'path' => $path,
            ]
        );
    }

    public function getLocalPublicStorageUrl(string $path = ''): string
    {
        return $this->localPublicStoragePath->getUrl($path);
    }
}
