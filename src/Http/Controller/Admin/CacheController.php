<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\View\View;
use Psr\Cache\CacheItemPoolInterface;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;

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
     * @return RedirectBackRenderer
     */
    public function clean(
        CacheItemPoolInterface $cache,
    ): RedirectBackRenderer {

        $cache->clear();

        return redirect()->back();
    }
}
