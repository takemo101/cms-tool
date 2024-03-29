<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\View\Accessor\DataAccessors;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\Chubby\Http\Bridge\BeforeControllerInvoke;
use Takemo101\CmsTool\Support\Accessor\ServerRequestAccessor;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;

#[AsEventListener(BeforeControllerInvoke::class)]
class RequestParameterSetupListener
{
    /**
     * constructor
     *
     * @param DataAccessors $accessors
     */
    public function __construct(
        private DataAccessors $accessors,
    ) {
        //
    }

    /**
     * @param BeforeControllerInvoke $event
     * @return void
     */
    public function __invoke(
        BeforeControllerInvoke $event
    ): void {
        $request = $event->getRequest();

        /** @var array<string,mixed> */
        $params = [
            ...$request->getQueryParams(),
            ...(array) $request->getParsedBody(),
        ];

        // Put old inputs to flash session.
        if (!empty($params)) {
            FlashSessionsContext::fromRequest($request)
                ?->getFlashSessions()
                ->get(FlashOldInputs::class)
                ->put($params);
        }
    }
}
