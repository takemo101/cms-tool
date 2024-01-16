<?php

namespace Takemo101\CmsTool\Support\Uri;

use DI\Attribute\Inject;
use Nyholm\Psr7\Uri;

class ApplicationUrl extends Uri
{
    /**
     * constructor
     *
     * @param string $uri
     */
    public function __construct(
        #[Inject('config.app.url')]
        string $uri = '',
    ) {
        parent::__construct($uri);
    }

    /**
     * reconstructor
     *
     * @param string $uri
     */
    public function reconstruct(string $uri = ''): void
    {
        $this->__construct($uri);
    }
}
