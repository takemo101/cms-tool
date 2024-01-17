<?php

namespace Takemo101\CmsTool\Http\Middleware;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CacheControl implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param array<string,string|integer|float|bool> $cacheControlHeaders
     */
    public function __construct(
        #[Inject('config.slim.cache_control')]
        private array $cacheControlHeaders,
    ) {
        //
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $response = $handler->handle($request);

        if (
            !$response->hasHeader('Cache-Control')
            && $header = $this->getCacheControlHeaderString()
        ) {
            $response = $response->withHeader(
                'Cache-Control',
                $header,
            );
        }

        return $response;
    }

    /**
     * Get Cache-Control header string
     *
     * @return string
     */
    public function getCacheControlHeaderString(): string
    {
        $headers = [];

        foreach ($this->cacheControlHeaders as $key => $value) {

            if (is_bool($value) && $value === true) {
                $headers[] = $key;
                continue;
            }

            if (is_string($value) || is_numeric($value)) {
                $headers[] = $key . '=' . $value;
            }
        }

        return implode(', ', array_filter($headers));
    }
}
