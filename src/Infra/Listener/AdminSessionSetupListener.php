<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\View\ViewCreator;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\CmsTool\Domain\Admin\RootAdminRepository;
use Takemo101\CmsTool\Infra\Event\AdminSessionStarted;

#[AsEventListener(AdminSessionStarted::class)]
class AdminSessionSetupListener
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
     * @param AdminSessionStarted $event
     * @return void
     */
    public function __invoke(
        AdminSessionStarted $event
    ): void {
        $admin = $event->getAdminSession();

        $this->view->share(
            'auth',
            $admin->isLoggedIn()
                ? $this->repository->find($admin->getId())
                : null,
        );
    }
}
