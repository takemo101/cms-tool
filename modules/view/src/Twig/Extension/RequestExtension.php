<?php

namespace CmsTool\View\Twig\Extension;

use Psr\Http\Message\ServerRequestInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use RuntimeException;

class RequestExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param ServerRequestInterface|null $request
     */
    public function __construct(
        private ?ServerRequestInterface $request = null,
    ) {
        //
    }

    /**
     * Set the value of request
     *
     * @param ServerRequestInterface $request
     * @return void
     */
    public function setServerRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Get the value of request
     *
     * @return ServerRequestInterface
     */
    private function getServerRequest(): ServerRequestInterface
    {
        $request = $this->request;

        $request ?? throw new RuntimeException('request is not set');

        return $request;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'query',
                $this->getQueryParams(...)
            ),
            new TwigFunction(
                'input',
                $this->getParsedBody(...),
            ),
        ];
    }

    /**
     * Get the value of query parameters
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getQueryParams(string $name, string $default = null): mixed
    {
        $params = $this->getServerRequest()->getQueryParams();

        return $params[$name] ?? $default;
    }

    /**
     * Get the value of body parameters
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getParsedBody(string $name, string $default = null): mixed
    {
        $params = $this->getServerRequest()->getParsedBody();

        return $params[$name] ?? $default;
    }
}
