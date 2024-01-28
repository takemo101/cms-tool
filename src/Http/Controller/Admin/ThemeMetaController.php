<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use CmsTool\Theme\Contract\ThemeSaver;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeMeta;
use CmsTool\Theme\ThemeQueryService;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\Chubby\Http\Renderer\RouteRedirectRenderer;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Http\Request\Admin\ChangeThemeMetaInputs;
use Takemo101\CmsTool\Http\Request\Admin\UpdateThemeMetaJsonRequest;
use Takemo101\CmsTool\Http\ViewModel\ThemeMetaPage;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Theme\Handler\ChangeThemeMetaHandler;

class ThemeMetaController
{
    /**
     * @param ServerRequestInterface $request
     * @param ThemeQueryService $queryService
     * @param string $id
     * @return View
     */
    public function edit(
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
            'cms-tool::theme.meta.edit',
            new ThemeMetaPage($theme),
        );
    }

    /**
     * @param ThemeQueryService $queryService
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param string $id
     * @return RedirectBackRenderer
     */
    public function update(
        ThemeQueryService $queryService,
        ServerRequestInterface $request,
        RequestValidator $validator,
        ChangeThemeMetaHandler $handler,
        string $id,
    ): RedirectBackRenderer|RouteRedirectRenderer {

        $form = $validator->throwIfFailed(
            $request,
            UpdateThemeMetaJsonRequest::class,
        );

        $payload = $form->getMetaPayload();

        $validator->throwIfFailedInputs(
            $payload,
            $request,
            ChangeThemeMetaInputs::class,
        );

        try {
            $theme = $handler->handle($id, $payload);
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return $theme->isReadonly()
            ? redirect()->route(
                'admin.theme.detail',
                [
                    'id' => $theme->id->value(),
                ],
            )
            : redirect()->back();
    }
}
