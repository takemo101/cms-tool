<?php

namespace Takemo101\CmsTool\Http\Controller;


readonly class LoginController
{
    public function loginPage()
    {
        return view('cms-tool::auth.login');
    }
}
