<?php

namespace Takemo101\CmsTool\Http\Action\Theme;

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Contract\ThemeAssetFinfoFactory;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\Chubby\Http\Renderer\StaticRenderer;

class AssetAction
{
    public const RouteName = 'theme.assets';

    /**
     * constructor
     *
     * @param ActiveTheme $activeTheme
     * @param ThemeAssetFinfoFactory $factory
     */
    public function __construct(
        private ActiveTheme $activeTheme,
        private ThemeAssetFinfoFactory $factory,
    ) {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $path
     * @return StaticRenderer
     */
    public function __invoke(ServerRequestInterface $request, string $path): StaticRenderer
    {
        $finfo = $this->factory->create($this->activeTheme, $path);

        if (!$finfo) {
            throw new HttpNotFoundException($request, " Asset not found: {$path}");
        }

        return (new StaticRenderer($finfo))
            ->enableAutoEtag()
            ->enableAutoLastModified();
    }
}
