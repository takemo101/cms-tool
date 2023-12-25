<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Domain\Theme\NotFoundThemeIdException;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Http\ViewModel\ThemeDetailPage;
use Takemo101\CmsTool\UseCase\Theme\Handler\ActivateThemeHandler;

class ThemeController
{
    /**
     * @param ServerRequestInterface $request
     * @param ThemeQueryService $queryService
     * @return View
     */
    public function index(
        ServerRequestInterface $request,
        ThemeQueryService $queryService,
    ): View {
        $themes = $queryService->getAll();

        return view(
            'cms-tool::theme.index',
            compact('themes'),
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ThemeQueryService $queryService
     * @param string $id
     * @return View
     */
    public function detail(
        ServerRequestInterface $request,
        ThemeQueryService $queryService,
        string $id,
    ): View {
        try {
            $theme = $queryService->getOne(new ThemeId($id));
        } catch (NotFoundThemeException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return view(
            'cms-tool::theme.detail',
            new ThemeDetailPage($theme),
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ActivateThemeHandler $handler
     * @param string $id
     * @return RedirectBackRenderer
     */
    public function activate(
        ServerRequestInterface $request,
        ActivateThemeHandler $handler,
        string $id,
    ): RedirectBackRenderer {
        try {
            $handler->handle($id);
        } catch (NotFoundThemeIdException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return redirect()->back();
    }
}
