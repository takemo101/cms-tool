<?php

namespace Takemo101\CmsTool\Error;

use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\ErrorHandler\ErrorSetting;
use Takemo101\Chubby\Http\ErrorHandler\HtmlErrorResponseRender;
use Takemo101\Chubby\Http\Renderer\HtmlRenderer;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Throwable;

class ErrorPageRender extends HtmlErrorResponseRender
{
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
        return new HtmlRenderer(
            <<<HTML
                <div>
                    <h1>Oops!</h1>
                    <p>Something went wrong.</p>
                    <p>{$exception->getMessage()}</p>
                </div>
            HTML
        );
    }
}
