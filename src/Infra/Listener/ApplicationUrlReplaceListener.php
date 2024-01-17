<?php

namespace Takemo101\CmsTool\Infra\Listener;

use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\CmsTool\Infra\Event\Setup;
use Takemo101\CmsTool\Support\Uri\ApplicationUrl;

#[AsEventListener(Setup::class)]
class ApplicationUrlReplaceListener
{
    /**
     * constructor
     *
     * @param ApplicationUrl $appUrl
     */
    public function __construct(
        private ApplicationUrl $appUrl,
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

        $this->appUrl->replace($request->getUri());
    }
}
