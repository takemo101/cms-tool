<?php

namespace CmsTool\Session\Csrf;

use CmsTool\Session\SessionAccessAdapter;
use DI\Attribute\Inject;
use Odan\Session\SessionInterface as Session;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfGuardFactory
{
    /**
     * constructor
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param integer $storageLimit
     * @param integer $strength
     * @param boolean $persistentTokenMode
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        #[Inject('config.session.csrf.storage_limit')]
        private int $storageLimit = 200,
        #[Inject('config.session.csrf.strength')]
        private int $strength = 16,
        #[Inject('config.session.csrf.persistent_token_mode')]
        private bool $persistentTokenMode = false
    ) {
        //
    }

    /**
     * Create CsrfGuard instance.
     *
     * @param Session $session
     * @return CsrfGuard
     */
    public function create(
        Session $session,
    ): CsrfGuard {

        $adapter = new SessionAccessAdapter($session);

        return new CsrfGuard(
            responseFactory: $this->responseFactory,
            storage: $adapter,
            failureHandler: $this->createFailureHandler(),
            storageLimit: $this->storageLimit,
            strength: $this->strength,
            persistentTokenMode: $this->persistentTokenMode
        );
    }

    /**
     * Create a CsrfGuard failure handler.
     *
     * @return callable
     */
    private function createFailureHandler(): callable
    {
        return fn (
            ServerRequestInterface $request,
            RequestHandlerInterface $handler,
        ) => throw new HttpCsrfTokenMismatchException($request);
    }
}
