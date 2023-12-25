<?php

namespace Takemo101\CmsTool\Support\Webhook;

interface WebhookHandler
{
    /**
     * Handle webhook payload
     *
     * @param array<string,mixed> $payload
     * @return void
     */
    public function handle(array $payload): void;
}
