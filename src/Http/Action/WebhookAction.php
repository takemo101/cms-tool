<?php

namespace Takemo101\CmsTool\Http\Action;

use DI\Attribute\Inject;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpUnauthorizedException;
use Takemo101\Chubby\Http\Renderer\JsonRenderer;
use Takemo101\CmsTool\Domain\Webhook\WebhookToken;
use Takemo101\CmsTool\Domain\Webhook\WebhookTokenRepository;
use Takemo101\CmsTool\Support\Webhook\WebhookExecutor;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;

class WebhookAction
{
    /**
     * constructor
     *
     * @param string $headerName
     * @param WebhookTokenRepository $repository
     */
    public function __construct(
        #[Inject('config.system.webhook.header')]
        private string $headerName,
        private WebhookTokenRepository $repository,
        private WebhookExecutor $executor,
    ) {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @return JsonRenderer
     */
    public function __invoke(
        ServerRequestInterface $request,
    ): JsonRenderer {
        $token = $this->repository->find();

        if (!$token) {
            throw InstallSettingException::notExistsSetting();
        }

        $headerToken = $request->getHeaderLine($this->headerName);

        if (
            empty($headerToken)
            || !$token->equals(new WebhookToken($headerToken))
        ) {
            throw new HttpUnauthorizedException($request, 'Unauthorized webhook request.');
        }

        /** @var array<string,mixed> */
        $payload = $request->getParsedBody();

        $this->executor->execute($payload);

        return new JsonRenderer([
            'message' => 'ok',
        ]);
    }
}
