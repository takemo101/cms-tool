<?php

namespace Takemo101\CmsTool\Domain\Webhook;

use CmsTool\Theme\ActiveTheme;

interface WebhookTokenRepository
{
    /**
     * Get the saved webhook token.
     * If it is not saved, return null
     *
     * @return WebhookToken|null
     */
    public function find(): ?WebhookToken;

    /**
     * Save web hook token
     *
     * @param WebhookToken $token
     * @return void
     */
    public function save(WebhookToken $token): void;
}
