<?php

namespace Takemo101\CmsTool\Support\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Slim\Interfaces\RouteParserInterface;
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
            new TwigFunction('vendor', [$this, 'getVendorAssetUrl']),
            new TwigFunction('storage', [$this, 'getLocalPublicStorageUrl']),
        ];
    }

    /**
     * Exists flash error messages?
     *
     * @return string
     */
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
