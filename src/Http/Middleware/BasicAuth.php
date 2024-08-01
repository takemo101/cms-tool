<?php

namespace Takemo101\CmsTool\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\CmsTool\Http\Exception\BasicAuthorizedException;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthenticator;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthHeaderParser;

/**
 * Basic authentication middleware.
 */
class BasicAuth implements MiddlewareInterface
{
    /**
     * The key for the authenticated user.
     */
    public const BasicAuthedKey = '__basic-authed__';

    /**
     * constructor
     *
     * @param BasicAuthenticator $authenticator
     * @param BasicAuthHeaderParser $parser
     * @param string $realm
     * @param boolean $enabled
     */
    public function __construct(
        private readonly BasicAuthenticator $authenticator,
        private readonly BasicAuthHeaderParser $parser = new BasicAuthHeaderParser(),
        private string $realm = 'Web',
        private bool $enabled = false,
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
     * @throws BasicAuthorizedException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if (
            $this->enabled === false ||
            $request->getAttribute(
                self::BasicAuthedKey,
                false,
            )
        ) {
            return $handler->handle($request);
        }

        $user = $this->parser->parseFromRequest($request);

        if (
            !(
                $user &&
                $this->authenticator->check(
                    $user->username,
                    $user->password,
                )
            )
        ) {
            throw new BasicAuthorizedException($this->realm);
        }

        $request = $request->withAttribute(
            self::BasicAuthedKey,
            true,
        );

        return $handler->handle($request);
    }

    /**
     * Set the enabled.
     *
     * @param boolean $enabled
     * @return self
     */
    public function setEnabled(bool $enabled = true): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Set the realm.
     *
     * @param string $realm
     * @return self
     */
    public function setRealm(string $realm): self
    {
        assert(
            empty($realm) === false,
            'realm is empty'
        );

        $this->realm = $realm;

        return $this;
    }
}
