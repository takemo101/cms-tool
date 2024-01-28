<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
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
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param string $id
     * @return RedirectBackRenderer
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        string $id,
    ): RedirectBackRenderer {

        $form = $validator->validate(
            $request,
            UpdateThemeMetaJsonRequest::class,
        );

        dd($form->getMetaArray());

        return redirect()->back();
    }
}
