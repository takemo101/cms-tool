<?php

namespace Takemo101\CmsTool\Support\Webhook;

use DI\FactoryInterface;
use Throwable;

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
     * @return void|never
     * @throws WebhookHandlingExceptions
     */
    public function execute(array $payload): void
    {
        /** @var Throwable[] */
        $throwables = [];

        foreach ($this->handlers->classes() as $handler) {
            if (is_string($handler)) {
                /** @var WebhookHandler */
                $handler = $this->factory->make($handler);
            }

            try {
                $handler->handle($payload);
            } catch (Throwable $e) {
                $throwables[] = $e;
            }
        }

        WebhookHandlingExceptions::throwIfNotEmpty(...$throwables);
    }
}
