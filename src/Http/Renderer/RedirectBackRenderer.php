<?php

namespace Takemo101\CmsTool\Http\Renderer;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;

class RedirectBackRenderer implements ResponseRenderer
{
    /**
     * constructor
     *
     * @param int $status
     * @param array<string,string> $headers
     */
    public function __construct(
        private int $status = StatusCodeInterface::STATUS_FOUND,
        private array $headers = []
    ) {
        //
    }

    /**
     * Set status code to be rendered.
     *
     * @param int $status
     * @return static
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set headers to be rendered.
     *
     * @param array<string,string> $headers
     * @return static
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Perform response writing process.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function render(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        foreach ($this->headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        $response = $response
            ->withStatus($this->status)
            ->withHeader('Location', $request->getHeaderLine('Referer') ?: '/');

        return $response;
    }
}
