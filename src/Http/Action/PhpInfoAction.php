<?php

namespace Takemo101\CmsTool\Http\Action;

class PhpInfoAction
{
    /**
     * Display phpinfo
     *
     * @return void
     */
    public function __invoke(): void
    {
        phpinfo();
    }
}
