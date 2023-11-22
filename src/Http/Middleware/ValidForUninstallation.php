<?php

namespace Takemo101\CmsTool\Http\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Domain\Install\InstallRepository;

class ValidForUninstallation implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param InstallRepository $repository
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        private InstallRepository $repository,
        private ResponseFactoryInterface $responseFactory,
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
        if ($this->repository->isInstalled()) {
            throw new HttpNotFoundException($request);
        }

        return $handler->handle($request);
    }
}
