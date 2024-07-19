<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use CmsTool\View\View;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Domain\Theme\NotFoundThemeIdException;
use Takemo101\CmsTool\Http\ViewModel\ThemeDetailPage;
use Takemo101\CmsTool\Http\ViewModel\ThemeIndexPage;
use Takemo101\CmsTool\Infra\Event\ThemeActivated;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Theme\Handler\ActivateThemeHandler;
use Takemo101\CmsTool\UseCase\Theme\Handler\CopyThemeHandler;
use Takemo101\CmsTool\UseCase\Theme\Handler\DeleteThemeHandler;

class ThemeController
{
    /**
     * @param ThemeQueryService $queryService
     * @return View
     */
    public function index(
        ThemeQueryService $queryService,
    ): View {
        $themes = $queryService->getAll();

        return view(
            'cms-tool::theme.index',
            new ThemeIndexPage($themes),
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
     * @param EventDispatcherInterface $dispatcher
     * @param string $id
     * @return ToastRenderer
     */
    public function activate(
        ServerRequestInterface $request,
        ActivateThemeHandler $handler,
        EventDispatcherInterface $dispatcher,
        string $id,
    ): ToastRenderer {
        try {
            $activeThemeId = $handler->handle($id);
        } catch (NotFoundThemeIdException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        $dispatcher->dispatch(new ThemeActivated($activeThemeId));

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Success,
            message: 'テーマを有効化しました'
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param CopyThemeHandler $handler
     * @param string $id
     * @return ToastRenderer
     */
    public function copy(
        ServerRequestInterface $request,
        CopyThemeHandler $handler,
        string $id,
    ): ToastRenderer {

        try {
            $copyTheme = $handler->handle($id);
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return toast(
            response: redirect()->route(
                'admin.theme.detail',
                ['id' => $copyTheme->id->value()]
            ),
            style: ToastStyle::Success,
            message: 'テーマを複製しました'
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param DeleteThemeHandler $handler
     * @param string $id
     * @return ToastRenderer
     */
    public function delete(
        ServerRequestInterface $request,
        DeleteThemeHandler $handler,
        string $id,
    ): ToastRenderer {
        try {
            $handler->handle($id);
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return toast(
            response: redirect()->route('admin.theme.index'),
            style: ToastStyle::Delete,
        );
    }
}
