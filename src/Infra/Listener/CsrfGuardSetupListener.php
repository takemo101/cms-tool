<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\Session\Event\CsrfGuardStarted;
use CmsTool\View\ViewCreator;
use Takemo101\Chubby\Event\Attribute\AsEventListener;

#[AsEventListener(CsrfGuardStarted::class)]
class CsrfGuardSetupListener
{
    /**
     * constructor
     *
     * @param ViewCreator $view
     */
    public function __construct(
        private readonly ViewCreator $view,
    ) {
        //
    }

    /**
     * @param CsrfGuardStarted $event
     * @return void
     */
    public function __invoke(
        CsrfGuardStarted $event
    ): void {
        // CsrfGuardContext generation processing is set in consideration of when the CSRF middleware is not executed.
        $token = $event->getGuard()->getToken();

        $this->view->share('csrf', $token);
    }
}
