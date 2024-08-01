<?php

namespace CmsTool\Support\AccessLog\Middleware;

use CmsTool\Support\AccessLog\AccessLogEntry;
use CmsTool\Support\AccessLog\AccessLogger;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use DateTimeImmutable;

class AccessLog implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param AccessLogger $logger
     * @param boolean $enabled
     */
    public function __construct(
        private readonly AccessLogger $logger,
        #[Inject('config.support.access_log.enabled')]
        private readonly bool $enabled = false,
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

        if ($this->enabled) {
            $this->logger->write(
                new AccessLogEntry(
                    datetime: new DateTimeImmutable(),
                    ip: $request->getServerParams()['REMOTE_ADDR'] ?? '',
                    uri: $request->getUri(),
                    method: $request->getMethod(),
                    status: (string) $response->getStatusCode(),
                    userAgent: $request->getHeaderLine('User-Agent'),
                    referer: $request->getHeaderLine('Referer'),
                ),
            );
        }

        return $response;
    }
}