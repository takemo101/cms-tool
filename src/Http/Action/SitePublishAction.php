<?php

namespace Takemo101\CmsTool\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Domain\Publish\SitePublishService;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;

class SitePublishAction
{
    /**
     * @param SitePublishService $service
     * @return RedirectBackRenderer
     * @throws HttpNotFoundException
     */
    public function __invoke(
        ServerRequestInterface $request,
        SitePublishService $service,
        string $status,
    ): RedirectBackRenderer {
        match ($status) {
            'published' => $service->publish(),
            'unpublished' => $service->unpublish(),
            default => throw new HttpNotFoundException($request),
        };

        return redirect()->back();
    }
}
