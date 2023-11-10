<?php

namespace CmsTool\View\Twig\Extension;

use Takemo101\Chubby\Http\Context;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use RuntimeException;

class ContextExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param Context|null $context
     */
    public function __construct(
        private ?Context $context = null,
    ) {
        //
    }

    /**
     * @return void
     */
    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    /**
     * Get the value of context
     *
     * @return Context
     */
    private function getContext(): Context
    {
        return $this->context ?? new RuntimeException('context is not set');
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'query',
                [$this, 'getQueryParams'],
            ),
            new TwigFunction(
                'input',
                [$this, 'getParsedBody'],
            ),
        ];
    }

    /**
     * Get the value of query parameters
     *
     * @param string $name
     * @param mixed $default
     * @return string
     */
    public function getQueryParams(string $name, string $default = null): mixed
    {
        $params = $this->getContext()->getRequest()->getQueryParams();

        return $params[$name] ?? $default;
    }

    /**
     * Get the value of body parameters
     *
     * @param string $name
     * @param mixed $default
     * @return string
     */
    public function getParsedBody(string $name, string $default = null): mixed
    {
        $params = $this->getContext()->getRequest()->getParsedBody();

        return $params[$name] ?? $default;
    }
}
