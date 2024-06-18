<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\View\ViewCreator;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\Chubby\Http\Event\BeforeControllerExecution;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Http\Session\AdminSessionContext;

#[AsEventListener(BeforeControllerExecution::class)]
class AdminSessionContextSetupListener
{
    /**
     * constructor
     *
     * @param ViewCreator $view
     * @param RootAdminRepository $repository
     */
    public function __construct(
        private ViewCreator $view,
        private RootAdminRepository $repository,
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

        // If there is an administrator session, pass the administrator information to the view
        if ($adminSession = AdminSessionContext::fromRequest($request)
            ?->getAdminSession()
        ) {
            $this->view
                ->share(
                    'auth',
                    $adminSession->isLoggedIn()
                        ? $this->repository->find($adminSession->getId())
                        : null,
                );
        }
    }
}
