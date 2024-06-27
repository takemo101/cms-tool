<?php

namespace Takemo101\CmsTool\Support\Htmx;

use App\Support\Htmx\HtmxRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that converts a redirection response to an Htmx-compatible redirection response if the request is from Htmx.
 */
class HtmxRedirector implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @throw HttpException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        $response = $handler->handle($request);

        // If the request is from Htmx and the response is a redirection response,
        if (
            (new HtmxRequest($request))->isHtmx() &&
            $this->isRedirectionResponse($response)
        ) {
            $response = HtmxRenderer::redirect($response->getHeaderLine('Location'))
                ->render(
                    request: $request,
                    response: $response,
                );
        }

        return $response;
    }

    /**
     * Determines if the response is a redirection response.
     *
     * @param ResponseInterface $response
     * @return bool
     */
    private function isRedirectionResponse(ResponseInterface $response): bool
    {
        return in_array(
            $response->getStatusCode(),
            [
                StatusCodeInterface::STATUS_MOVED_PERMANENTLY,
                StatusCodeInterface::STATUS_FOUND,
                StatusCodeInterface::STATUS_SEE_OTHER,
                StatusCodeInterface::STATUS_TEMPORARY_REDIRECT,
                StatusCodeInterface::STATUS_PERMANENT_REDIRECT,
            ]
        );
    }
}
