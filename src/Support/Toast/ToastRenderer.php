<?php

namespace Takemo101\CmsTool\Support\Toast;

use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\SessionContext;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Contract\Arrayable;
use Takemo101\Chubby\Contract\ContainerInjectable;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;

/**
 * @template T of ResponseRenderer
 */
class ToastRenderer implements ResponseRenderer, Arrayable, ContainerInjectable
{
    /**
     * Toast style session key
     *
     * @var string
     */
    public const ToastStyleKey = 'toast-style';

    /**
     * Toast message session key
     *
     * @var string
     */
    public const ToastMessageKey = 'toast-message';

    /**
     * constructor
     *
     * @param T $response
     * @param ToastStyle $style
     * @param string|null $message
     */
    public function __construct(
        private readonly ResponseRenderer $response,
        private ToastStyle $style = ToastStyle::Success,
        private ?string $message = null,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ApplicationContainer $container): void
    {
        if ($this->response instanceof ContainerInjectable) {
            $this->response->setContainer($container);
        }
    }

    /**
     * Set toast style to session.
     *
     * @return self
     */
    public function setStyle(ToastStyle $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Set toast message to session.
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the response renderer
     *
     * @return T
     */
    public function response(): ResponseRenderer
    {
        return $this->response;
    }

    /**
     * Get the toast information to be saved in the session
     *
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            self::ToastStyleKey => $this->style,
            self::ToastMessageKey => $this->message ?? $this->style->message(),
        ];
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

        $toast = FlashSessionsContext::fromRequest($request)
            ->getFlashSessions()
            ->get(FlashToast::class);

        $toast->put($this->toArray());

        return $this->response->render($request, $response);
    }
}
