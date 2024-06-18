<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\Session\Csrf\CsrfGuardContext;
use CmsTool\Session\Csrf\CsrfToken;
use CmsTool\View\ViewCreator;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\Chubby\Http\Event\BeforeControllerExecution;
use Takemo101\CmsTool\Support\FormAppendFilter\AppendCsrfInputFilter;

#[AsEventListener(BeforeControllerExecution::class)]
class CsrfGuardContextSetupListener
{
    /**
     * constructor
     *
     * @param ContainerInterface $container
     * @param ViewCreator $view
     * @param AppendCsrfInputFilter $filter
     */
    public function __construct(
        private ContainerInterface $container,
        private ViewCreator $view,
        private AppendCsrfInputFilter $filter,
    ) {
        //
    }

    /**
     * @param BeforeControllerExecution $event
     * @return void
     */
    public function __invoke(
        BeforeControllerExecution $event
    ): void {
        $request = $event->getRequest();

        // CsrfGuardContext generation processing is set in consideration of when the CSRF middleware is not executed.
        if ($token = CsrfGuardContext::fromRequest(
            $request,
        )
            ?->getGuard()
            ->getToken() ?? CsrfToken::empty()
        ) {
            $this->filter->setCsrfToken($token);

            $this->view->share('csrf', $token);
        }
    }
}
