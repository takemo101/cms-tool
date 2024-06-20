<?php

namespace Takemo101\CmsTool\Http\Middleware;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\HookTags;
use Takemo101\CmsTool\Http\Session\AdminSessionContext;
use Takemo101\CmsTool\Http\Session\AdminSessionFactory;
use Takemo101\CmsTool\Infra\Event\AdminSessionStarted;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;

/**
 * If you are using DefaultAdminsession, you must use Sessionstart middleware
 */
class AdminSessionStart implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param AdminSessionFactory $factory
     * @param EventDispatcherInterface $dispatcher
     * @param Hook $hook
     */
    public function __construct(
        private readonly AdminSessionFactory $factory,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly Hook $hook,
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
        $session = $this->factory->create($request);

        $context = new AdminSessionContext($session);

        $request = $context->withRequest($request);

        $this->hook->do(
            tag: AdminSession::class,
            parameter: $session,
        );

        $this->hook->do(
            tag: HookTags::AdminSessionStarted,
            parameter: $session,
        );

        $this->dispatcher->dispatch(
            new AdminSessionStarted(
                request: $request,
                admin: $session,
            ),
        );

        return $handler->handle($request);
    }
}
