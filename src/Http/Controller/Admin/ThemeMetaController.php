<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\ThemeId;
use CmsTool\Theme\ThemeQueryService;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Http\Request\Admin\ChangeThemeMetaInputs;
use Takemo101\CmsTool\Http\Request\Admin\UpdateThemeMetaJsonRequest;
use Takemo101\CmsTool\Http\ViewModel\ThemeMetaPage;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
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

        if (!$theme->canBeEdited()) {
            throw new HttpNotFoundException($request, 'Theme is readonly.');
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
     * @return ToastRenderer
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        ChangeThemeMetaHandler $handler,
        string $id,
    ): ToastRenderer {

        $form = $validator->throwIfFailed(
            $request,
            UpdateThemeMetaJsonRequest::class,
        );

        $payload = $form->getMetaPayload();

        $formRequest = $validator->throwIfFailedInputs(
            $payload,
            $request,
            ChangeThemeMetaInputs::class,
        );

        try {
            $theme = $handler->handle($id, $payload);
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        } catch (ArrayKeyMissingException $e) {
            throw HttpValidationErrorException::fromMessages(
                messages: [
                    "schema[any].settings[any][{$e->getKey()}]" => [
                        $e->getMessage(),
                    ],
                ],
                request: $request,
            );
        }

        return toast(
            response: $theme->isReadonly()
                ? redirect()->route(
                    'admin.theme.detail',
                    [
                        'id' => $theme->id->value(),
                    ],
                )
                : redirect()->back(),
            style: ToastStyle::Update,
        );
        ;
    }
}
