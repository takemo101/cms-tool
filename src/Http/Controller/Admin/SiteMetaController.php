<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Http\Request\Admin\ChangeSiteNameRequest;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\ChangeSiteNameHandler;

class SiteMetaController
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param ChangeSiteNameHandler $handler
     * @return RedirectBackRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        ChangeSiteNameHandler $handler,
    ): RedirectBackRenderer {
        $form = $validator->throwIfFailed(
            $request,
            ChangeSiteNameRequest::class,
        );

        $handler->handle($form->siteName);

        return redirect()->back();
    }
}
