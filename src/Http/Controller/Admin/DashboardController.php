<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

class DashboardController
{
    /**
     * Tool dashboard page
     *
     * @return View
     */
    public function dashboardPage()
    {
        return view('cms-tool::dashboard');
    }
}
