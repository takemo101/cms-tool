<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\Session\Event\SessionStarted;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;

#[AsEventListener(SessionStarted::class)]
class RequestParameterSetupListener
{
    /**
     * @param SessionStarted $event
     * @return void
     */
    public function __invoke(
        SessionStarted $event
    ): void {
        $request = $event->getRequest();

        /** @var array<string,mixed> */
        $params = [
            ...$request->getQueryParams(),
            ...(array) $request->getParsedBody(),
        ];

        // Put old inputs to flash session.
        if (!empty($params)) {
            $event->getFlashSessions()
                ->get(FlashOldInputs::class)
                ->put($params);
        }
    }
}
