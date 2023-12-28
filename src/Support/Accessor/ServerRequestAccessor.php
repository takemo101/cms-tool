<?php

namespace Takemo101\CmsTool\Support\Accessor;

use ArrayObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ServerRequestAccessor
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
     * @return ArrayObject
     */
    public function __invoke(): ArrayObject
    {
        $header = $this->request->getHeaders();
        $body = $this->request->getParsedBody();
        $query = $this->request->getQueryParams();
        $cookie = $this->request->getCookieParams();
        $server = $this->request->getServerParams();

        $uri = $this->request->getUri();

        return ImmutableArrayObject::of([
            'header' => $header,
            'body' => $body ?? [],
            'query' => $query,
            'cookie' => $cookie,
            'server' => $server,
            'uri' => [
                'scheme' => $uri->getScheme(),
                'authority' => $uri->getAuthority(),
                'host' => $uri->getHost(),
                'port' => $uri->getPort(),
                'path' => $uri->getPath(),
                'query' => $uri->getQuery(),
                'fragment' => $uri->getFragment(),
                'base' => $this->getBasePath($uri),
            ],
        ]);
    }

    /**
     * @param UriInterface $uri
     * @return string
     */
    private function getBasePath(UriInterface $uri): string
    {
        $schema = $uri->getScheme();
        $authority = $uri->getAuthority();

        $base = ($schema
            ? $schema . ':'
            : ''
        ) . ($authority ? '//' . $authority : '');

        return $base;
    }
}
