<?php

namespace Takemo101\CmsTool\Http\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\CmsTool\UseCase\TrackingCode\QueryService\TrackingCodeQueryService;

class InsertTrackingCode implements MiddlewareInterface
{
    /**
     * constructor
     *
     * @param TrackingCodeQueryService $repository
     */
    public function __construct(
        private TrackingCodeQueryService $queryService,
    ) {
        //
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $response = $handler->handle($request);

        if (
            $response->getStatusCode() !== StatusCodeInterface::STATUS_OK
            || $request->getMethod() !== 'GET'
            || $response->getHeaderLine('content-type') !== 'text/html'
        ) {
            return $response;
        }

        $tracking = $this->queryService->get();

        $body = (string) $response->getBody();

        $count = 0;

        if ($tracking->head) {
            $body = preg_replace(
                '/<head>/',
                '<head>' . $tracking->head,
                $body,
                1,
                $count,
            );
        }

        if ($tracking->body) {
            $body = preg_replace(
                '/(<body[^>]*>)/',
                '$1' . $tracking->body,
                $body,
                1,
                $count,
            );
        }

        if ($tracking->footer) {
            $body = preg_replace(
                '/<\/body>/',
                $tracking->footer . '</body>',
                $body,
                1,
                $count,
            );
        }

        if ($count) {
            $response->getBody()->rewind();
            $response->getBody()->write($body);
        }

        return $response;
    }
}
