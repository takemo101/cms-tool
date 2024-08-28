<?php

namespace Takemo101\CmsTool\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Domain\Publish\SitePublishService;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;

class SitePublishAction
{
    /**
     * @param SitePublishService $service
     * @return ToastRenderer<RedirectBackRenderer>
     * @throws HttpNotFoundException
     */
    public function __invoke(
        ServerRequestInterface $request,
        SitePublishService $service,
        string $status,
    ): ToastRenderer {
        match ($status) {
            'published' => $service->publish(),
            'unpublished' => $service->unpublish(),
            default => throw new HttpNotFoundException($request),
        };

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Success,
            message: match ($status) {
                'published' => 'サイトを公開しました',
                default => 'サイトを非公開にしました',
            },
        );
    }
}
