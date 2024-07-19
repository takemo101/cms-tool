<?php

namespace Takemo101\CmsTool\Support\Htmx;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;

/**
 * Middleware to control access via Htmx.
 */
class HtmxAccess implements MiddlewareInterface
{
    /**
     * Status code to use when the access is not via Htmx.
     */
    public const ErrorStatusCode = StatusCodeInterface::STATUS_FORBIDDEN;

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @throw HttpException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if (!(new HtmxRequest($request))->isHtmx()) {
            throw new HttpException(
                request: $request,
                message: 'This request is not from Htmx.',
                code: self::ErrorStatusCode,
            );
        }

        return $handler->handle($request);
    }
}
