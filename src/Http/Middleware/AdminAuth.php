<?php

namespace Takemo101\CmsTool\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Takemo101\Chubby\Http\ErrorHandler\InterruptRender;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Takemo101\CmsTool\Http\Exception\HttpAuthorizedException;
use Takemo101\CmsTool\Http\Session\AdminSessionContext;

class AdminAuth implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param ResponseRenderer|null $renderer
     */
    public function __construct(
        private $isRequiredLogin = true,
        private ?ResponseRenderer $renderer,
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
        $session = AdminSessionContext::fromServerRequest($request)
            ->getAdminSession();

        if ($this->isRequiredLogin && !$session->isLoggedIn()) {
            $this->notLoggedInError($request);
        }

        if (!$this->isRequiredLogin && $session->isLoggedIn()) {
            $this->loggedInError($request);
        }

        return $handler->handle($request);
    }

    /**
     * Processing if you have not logged in
     *
     * @param ServerRequestInterface $request
     * @return never
     * @throws HttpUnauthorizedException|InterruptRender
     */
    private function notLoggedInError(
        ServerRequestInterface $request,
    ): never {
        if (!$this->renderer) {
            throw new HttpUnauthorizedException($request);
        }

        throw new InterruptRender($this->renderer);
    }

    /**
     * Processing when logging in
     *
     * @param ServerRequestInterface $request
     * @return never
     * @throws HttpAuthorizedException|InterruptRender
     */
    private function loggedInError(
        ServerRequestInterface $request,
    ): never {
        if (!$this->renderer) {
            throw new HttpAuthorizedException($request);
        }

        throw new InterruptRender($this->renderer);
    }

    /**
     * Generate middleware that requires authentication
     * If you are not logged in, you will be redirected to the login screen
     *
     * @param ResponseRenderer|null $renderer
     * @return self
     */
    public static function auth(?ResponseRenderer $renderer = null): self
    {
        return new self(
            isRequiredLogin: true,
            renderer: $renderer,
        );
    }

    /**
     * Generate middleware that does not require authentication
     * If you are logged in, you will be redirected to the top page
     *
     * @param ResponseRenderer|null $renderer
     * @return self
     */
    public static function guest(?ResponseRenderer $renderer = null): self
    {
        return new self(
            isRequiredLogin: false,
            renderer: $renderer,
        );
    }
}
