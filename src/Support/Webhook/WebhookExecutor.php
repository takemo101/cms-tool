<?php

namespace Takemo101\CmsTool\Support\Webhook;

use DI\FactoryInterface;

class WebhookExecutor
{
    /**
     * constructor
     *
     * @param FactoryInterface $factory
     * @param WebhookHandlers $handlers
     */
    public function __construct(
        private FactoryInterface $factory,
        private WebhookHandlers $handlers,
    ) {
        //
    }

    /**
     * Execute WebHook processing together
     *
     * @param array<string,mixed> $payload
     * @return void
     */
    public function execute(array $payload): void
    {
        foreach ($this->handlers->classes() as $handler) {
            if (is_string($handler)) {
                /** @var WebhookHandler */
                $handler = $this->factory->make($handler);
            }

            $handler->handle($payload);
        }
    }
}
