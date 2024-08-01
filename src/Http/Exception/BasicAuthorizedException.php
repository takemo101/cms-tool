<?php

namespace Takemo101\CmsTool\Http\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;

/**
 * Basic authorized exception
 */
class BasicAuthorizedException extends Exception implements ResponseRenderer
{
    /**
     * constructor
     *
     * @param string $realm
     */
    public function __construct(
        private readonly string $realm,
    ) {
        parent::__construct(
            message: '401 Unauthorized',
            code: StatusCodeInterface::STATUS_UNAUTHORIZED,
        );
    }

    /**
     * Get the realm
     *
     * @return string
     */
    public function getRealm(): string
    {
        return $this->realm;
    }

    /**
     * {@inheritDoc}
     */
    public function render(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $response = $response
            ->withStatus($this->getCode())
            ->withHeader('WWW-Authenticate', 'Basic realm="' . $this->realm . '"');

        $response->getBody()->write($this->getMessage());

        return $response;
    }
}
