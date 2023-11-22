<?php

namespace Takemo101\CmsTool\Error;

use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Support\Validation\HttpValidationErrorException;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\ErrorHandler\AbstractErrorResponseRender;
use Takemo101\Chubby\Http\ErrorHandler\ErrorSetting;
use Takemo101\Chubby\Http\Renderer\JsonRenderer;
use Takemo101\Chubby\Http\Renderer\RedirectRenderer;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;
use Throwable;

class ValidationErrorResponseRender extends AbstractErrorResponseRender
{
    /**
     * Determine if the response should be rendered.
     *
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param ErrorSetting $setting
     *
     * @return bool
     */
    protected function shouldRender(
        ServerRequestInterface $request,
        Throwable $exception,
        ErrorSetting $setting,
    ): bool {
        return $exception instanceof HttpValidationErrorException;
    }

    /**
     * Create error response renderer.
     *
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param ErrorSetting $setting
     *
     * @return ResponseRenderer
     */
    protected function createRenderer(
        ServerRequestInterface $request,
        Throwable $exception,
        ErrorSetting $setting,
    ): ResponseRenderer {

        $accept = $request->getHeaderLine('Accept');

        return str_contains($accept, 'text/html')
            ? $this->createRedirectRenderer($request, $exception)
            : $this->createJsonRenderer($exception);
    }

    /**
     * @param ServerRequestInterface $request
     * @param HttpValidationErrorException $exception
     * @return ResponseRenderer
     */
    public function createRedirectRenderer(
        ServerRequestInterface $request,
        HttpValidationErrorException $exception,
    ): ResponseRenderer {

        $flashErrorMessages = FlashSessionsContext::fromServerRequest($request)
            ->getFlashSessions()
            ->get(FlashErrorMessages::class);

        $flashErrorMessages->put(
            $exception->getErrorMessages(),
        );

        return new RedirectRenderer(
            $request->getHeaderLine('Referer'),
        );
    }

    /**
     * @param HttpValidationErrorException $exception
     * @return ResponseRenderer
     */
    public function createJsonRenderer(
        HttpValidationErrorException $exception,
    ): ResponseRenderer {

        $messages = $exception->getErrorMessages();

        return new JsonRenderer(
            [
                'code' => $exception->getCode(),
                'errors' => $messages,
            ],
            $exception->getCode(),
        );
    }
}
