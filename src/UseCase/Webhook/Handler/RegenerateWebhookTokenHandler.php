<?php

namespace Takemo101\CmsTool\UseCase\Webhook\Handler;

use Takemo101\CmsTool\Domain\Webhook\WebhookToken;
use Takemo101\CmsTool\Domain\Webhook\WebhookTokenRepository;
use Takemo101\CmsTool\UseCase\Shared\Exception\InstallSettingException;

class RegenerateWebhookTokenHandler
{
    /**
     * constructor
     *
     * @param WebhookTokenRepository $repository
     */
    public function __construct(
        private WebhookTokenRepository $repository,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @return WebhookToken
     * @throws InstallSettingException
     */
    public function handle(): WebhookToken
    {
        $token = $this->repository->find();

        if (!$token) {
            throw InstallSettingException::notExistsSetting();
        }

        $regeneratedToken = $token->regenerate();

        $this->repository->save($regeneratedToken);

        return $regeneratedToken;
    }
}
