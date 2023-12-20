<?php

namespace Takemo101\CmsTool\Http\Action\Theme;

use CmsTool\View\View;

class HomeAction
{
    /**
     * @return View
     */
    public function __invoke(): View
    {
        return view('pages.home');
    }
}
