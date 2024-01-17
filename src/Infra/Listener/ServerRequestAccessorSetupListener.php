<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\View\Accessor\DataAccessors;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\CmsTool\Infra\Event\Setup;
use Takemo101\CmsTool\Support\Accessor\ServerRequestAccessor;

#[AsEventListener(Setup::class)]
class ServerRequestAccessorSetupListener
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
     * @param Setup $event
     * @return void
     */
    public function __invoke(
        Setup $event
    ): void {
        $request = $event->getRequest();

        // Make a request from View via Dataaccessors
        $this->accessors->add(
            'request',
            ServerRequestAccessor::class,
            [
                'request' => $request,
            ]
        );
    }
}
