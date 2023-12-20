<?php

namespace Takemo101\CmsTool\Http\Action;

use CmsTool\Theme\Contract\ThemeAssetFinfoFactory;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\Chubby\Http\Renderer\StaticRenderer;

class ThemeAssetAction
{
    public const RouteName = 'admin.theme.assets';

    /**
     * constructor
     *
     * @param ThemeQueryService $queryService
     * @param ThemeAssetFinfoFactory $factory
     */
    public function __construct(
        private ThemeQueryService $queryService,
        private ThemeAssetFinfoFactory $factory,
    ) {
        //
    }

    /**
     * Hello to you
     *
     * @param ServerRequestInterface $request
     * @param string $id
     * @param string $path
     * @return StaticRenderer
     */
    public function __invoke(ServerRequestInterface $request, string $id, string $path): StaticRenderer
    {
        $themeId = new ThemeId($id);

        try {
            $theme = $this->queryService->getOne($themeId);
        } catch (NotFoundThemeException $e) {
            throw new HttpNotFoundException($request, " Theme not found: {$id}");
        }

        $finfo = $this->factory->create($theme, $path);

        if (!$finfo) {
            throw new HttpNotFoundException($request, " Asset not found: {$path}");
        }

        return (new StaticRenderer($finfo))
            ->enableAutoEtag()
            ->enableAutoLastModified();
    }
}
