<?php

namespace Takemo101\CmsTool\Support\Accessor;

use ArrayObject;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Context\ContextRepository;
use Takemo101\Chubby\Http\Uri\UriProxy;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ServerRequestAccessor
{
    /**
     * constructor
     *
     * @param ContextRepository $repository
     */
    public function __construct(
        private ContextRepository $repository,
    ) {
        //
    }

    /**
     * @return ArrayObject
     */
    public function __invoke(): ArrayObject
    {
        $request = $this->repository->get()->getTyped(ServerRequestInterface::class);

        $header = $request->getHeaders();
        $body = $request->getParsedBody();
        $query = $request->getQueryParams();
        $cookie = $request->getCookieParams();
        $server = $request->getServerParams();

        $uri = new UriProxy($request->getUri());

        return ImmutableArrayObject::of([
            'header' => $header,
            'body' => $body ?? [],
            'query' => $query,
            'cookie' => $cookie,
            'server' => $server,
            'uri' => [
                'full' => $uri->__toString(),
                'current' => $uri->getCurrent(),
                'scheme' => $uri->getScheme(),
                'authority' => $uri->getAuthority(),
                'host' => $uri->getHost(),
                'port' => $uri->getPort(),
                'path' => $uri->getPath(),
                'query' => $uri->getQuery(),
                'fragment' => $uri->getFragment(),
                'base' => $uri->getBase(),
            ],
        ]);
    }
}
