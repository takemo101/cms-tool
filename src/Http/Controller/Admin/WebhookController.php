<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Takemo101\CmsTool\Domain\Webhook\WebhookTokenRepository;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\Webhook\Handler\RegenerateWebhookTokenHandler;

class WebhookController
{
    /**
     * @param ServerRequestInterface $request
     * @param WebhookTokenRepository $repository
     * @return View
     */
    public function edit(
        ServerRequestInterface $request,
        WebhookTokenRepository $repository,
    ): View {

        $token  = $repository->find();

        $url = RouteContext::fromRequest($request)
            ->getRouteParser()
            ->fullUrlFor(
                $request->getUri(),
                'webhook',
            );

        $header = config('system.webhook.header', 'X-CMSTOOL-WEBHOOK-TOKEN');

        return view(
            'cms-tool::webhook.edit',
            compact('token', 'url', 'header'),
        );
    }

    /**
     * @param RegenerateWebhookTokenHandler $handler
     * @return ToastRenderer
     */
    public function regenerate(
        RegenerateWebhookTokenHandler $handler,
    ): ToastRenderer {
        $handler->handle();

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Update,
        );
    }
}
