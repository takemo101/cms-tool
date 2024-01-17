<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Http\Request\Admin\SaveTrackingCodeRequest;
use Takemo101\CmsTool\UseCase\TrackingCode\Handler\SaveTrackingCodeCommand;
use Takemo101\CmsTool\UseCase\TrackingCode\Handler\SaveTrackingCodeHandler;
use Takemo101\CmsTool\UseCase\TrackingCode\QueryService\TrackingCodeQueryService;

class TrackingCodeController
{
    /**
     * @param TrackingCodeQueryService $queryService
     * @return View
     */
    public function edit(
        TrackingCodeQueryService $queryService,
    ): View {
        return view(
            'cms-tool::seo.tracking',
            [
                'tracking' => $queryService->get(),
            ]
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param SaveTrackingCodeHandler $handler
     * @return RedirectBackRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveTrackingCodeHandler $handler,
    ): RedirectBackRenderer {
        $form = $validator->throwIfFailed(
            $request,
            SaveTrackingCodeRequest::class,
        );

        $handler->handle(
            new SaveTrackingCodeCommand(
                head: $form->head,
                body: $form->body,
                footer: $form->footer,
            ),
        );

        return redirect()->back();
    }
}
