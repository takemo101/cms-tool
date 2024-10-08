<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\View\View;
use Psr\Cache\CacheItemPoolInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Infra\Event\Uninstalled;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
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
     * @return ToastRenderer<RedirectBackRenderer>
     */
    public function uninstall(
        UninstallHandler $handler,
        AdminSession $session,
        CacheItemPoolInterface $cache,
        EventDispatcherInterface $dispatcher
    ): ToastRenderer {

        $handler->handle();

        $cache->clear();

        $session->logout();

        $dispatcher->dispatch(new Uninstalled());

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Success,
            message: <<<MES
                アンインストールが完了しました
                再度ご利用する場合は再インストールが必要です
            MES,
        );
    }
}
