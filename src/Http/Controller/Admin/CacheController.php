<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\View\View;
use Psr\Cache\CacheItemPoolInterface;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Infra\Cache\ApiMemoCache;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;

class CacheController
{
    /**
     * @return View
     */
    public function confirm(): View
    {
        return view('cms-tool::cache.confirm');
    }

    /**
     * @param CacheItemPoolInterface $cache
     * @param ApiMemoCache $memo
     * @return ToastRenderer<RedirectBackRenderer>
     */
    public function clean(
        CacheItemPoolInterface $cache,
        ApiMemoCache $memo,
    ): ToastRenderer {

        $cache->clear();
        $memo->clear();

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Success,
            message: <<<MES
                キャッシュをクリアしました
            MES,
        );
    }
}
