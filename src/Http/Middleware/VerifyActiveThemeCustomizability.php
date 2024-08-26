<?php

namespace Takemo101\CmsTool\Http\Middleware;

use CmsTool\Theme\ActiveTheme;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;

/**
 * Middleware to check if the active theme is customizable.
 */
class VerifyActiveThemeCustomizability implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param ActiveTheme $activeTheme
     */
    public function __construct(
        private readonly ActiveTheme $activeTheme,
    ) {
        //
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @throws HttpForbiddenException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if (!$this->activeTheme->canBeCustomized()) {
            throw new HttpForbiddenException(
                request: $request,
                message: 'The theme cannot be customized',
            );
        }

        return $handler->handle($request);
    }
}
