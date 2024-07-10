<?php

namespace Takemo101\CmsTool\Http\Renderer;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\JsonRenderer;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;

class UpdatedJsonRenderer implements ResponseRenderer
{
    /**
     * constructor
     *
     * @param string $message
     */
    public function __construct(
        private string $message = 'Updated successfully',
    ) {
        //
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
        return (new JsonRenderer(
            data: [
                'message' => $this->message,
            ],
            status: StatusCodeInterface::STATUS_NO_CONTENT,
        ))->render($request, $response);
    }
}
