<?php

namespace Takemo101\CmsTool\Preset\Shared\Feed;

use CmsTool\Support\Feed\Feed;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\ApplicationContainer;

interface FeedCreator
{
    /**
     * Create a feed object.
     *
     * @param ServerRequestInterface $request
     * @param ApplicationContainer $container
     * @return Feed
     */
    public function create(
        ServerRequestInterface $request,
        ApplicationContainer $container,
    ): Feed;
}
