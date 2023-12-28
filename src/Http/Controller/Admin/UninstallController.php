<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\View\View;
use Psr\Cache\CacheItemPoolInterface;
use Takemo101\Chubby\Http\Renderer\RouteRedirectRenderer;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Install\Handler\UninstallHandler;

class UninstallController
{
    /**
     * @return View
     */
    public function confirm(): View
    {
        return view('cms-tool::uninstall.confirm');
    }

    /**
     * @return RouteRedirectRenderer
     */
    public function uninstall(
        UninstallHandler $handler,
        AdminSession $session,
        CacheItemPoolInterface $cache,
    ): RouteRedirectRenderer {

        $handler->handle();

        $cache->clear();

        $session->logout();

        return redirect()->route('home');
    }
}
