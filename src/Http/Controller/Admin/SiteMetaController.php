<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Http\Request\Admin\ChangeSiteNameRequest;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\ChangeSiteNameHandler;

class SiteMetaController
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param ChangeSiteNameHandler $handler
     * @return ToastRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        ChangeSiteNameHandler $handler,
    ): ToastRenderer {
        $form = $validator->throwIfFailed(
            $request,
            ChangeSiteNameRequest::class,
        );

        $handler->handle($form->siteName);

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Update,
        );
    }
}
