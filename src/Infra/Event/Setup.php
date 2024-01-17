<?php

namespace Takemo101\CmsTool\Infra\Event;

use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Event\StoppableEvent;

class Setup extends StoppableEvent
{
    /**
     * constructor
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(
        private ServerRequestInterface $request,
    ) {
        //
    }

    /**
     * Get request
     *
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
