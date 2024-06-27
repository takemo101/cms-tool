<?php

namespace App\Support\Shared;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Contract\ContainerInjectable;
use Takemo101\Chubby\Contract\Renderable;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Takemo101\Chubby\Support\ServiceLocator;

/**
 * 継承による汎用的なレスポンス処理を提供する抽象クラス
 */
abstract class AbstractResponseRenderer implements ResponseRenderer, ContainerInjectable
{
    /**
     * @var ApplicationContainer|null
     */
    private ?ApplicationContainer $container = null;

    /**
     * constructor
     *
     * @param string|Renderable|ResponseRenderer $content
     * @param int $code
     * @param array<string,string> $headers
     */
    public function __construct(
        private string|Renderable|ResponseRenderer $content = '',
        private int $code = StatusCodeInterface::STATUS_OK,
        private array $headers = [],
    ) {
        //
    }

    /**
     * Set content to be rendered.
     *
     * @param string|Renderable|ResponseRenderer $content
     * @return static
     */
    public function setContent(
        string|Renderable|ResponseRenderer $content
    ): static {
        $this->content = $content;

        return $this;
    }

    /**
     * Set status code to be rendered.
     *
     * @param integer $code
     * @return static
     */
    public function setStatusCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set content to be rendered.
     *
     * @param string|View $content
     * @return static
     */
    public function addHeader(string $name, string $value): static
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ApplicationContainer $container): void
    {
        $this->container = $container;
    }

    /**
     * Get the application container implementation.
     *
     * @return ApplicationContainer
     */
    private function getContainer(): ApplicationContainer
    {
        return $this->container ?? ServiceLocator::container();
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

        $content = $this->content;

        // If the content is a container injectable, set the container
        if ($content instanceof ContainerInjectable) {
            $content->setContainer($this->getContainer());
        }

        // If the content is a response renderer, render it
        if ($content instanceof ResponseRenderer) {
            $response = $content->render($request, $response);
        } else {

            $content = match (true) {
                $content instanceof Renderable => $content->render(),
                default => $content,
            };

            $response->getBody()->write($content);
        }

        // Set status code
        $response = $response->withStatus($this->code);

        // Set headers
        foreach ($this->headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }
}
