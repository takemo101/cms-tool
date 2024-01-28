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
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Http\Request\Admin\ChangeThemeMetaInputs;
use Takemo101\CmsTool\Http\Request\Admin\UpdateThemeMetaJsonRequest;
use Takemo101\CmsTool\Http\ViewModel\ThemeMetaPage;

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
        ThemeSaver $saver,
        ServerRequestInterface $request,
        RequestValidator $validator,
        string $id,
    ): RedirectBackRenderer {

        try {
            $theme = $queryService->getOne(new ThemeId($id));
        } catch (NotFoundThemeException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        $form = $validator->throwIfFailed(
            $request,
            UpdateThemeMetaJsonRequest::class,
        );

        $meta = $form->getMetaArray();

        $validator->throwIfFailedInputs(
            $meta,
            $request,
            ChangeThemeMetaInputs::class,
        );

        $changed = $theme->changeMeta(ThemeMeta::fromArray($form->getMetaArray()));

        $saver->save($changed);

        return redirect()->back();
    }
}
