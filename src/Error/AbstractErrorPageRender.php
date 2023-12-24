<?php

namespace Takemo101\CmsTool\Error;

use CmsTool\View\Contract\TemplateFinder;
use DI\Attribute\Inject;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\ErrorHandler\ErrorSetting;
use Takemo101\Chubby\Http\ErrorHandler\HtmlErrorResponseRender;
use Takemo101\Chubby\Http\Renderer\HtmlRenderer;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Takemo101\CmsTool\Error\ViewModel\ErrorPage;
use Throwable;

abstract class AbstractErrorPageRender extends HtmlErrorResponseRender
{
    /**
     * constructor
     *
     * @param string $systemRoutePath
     */
    public function __construct(
        private TemplateFinder $finder,
        #[Inject('config.system.route')]
        private string $systemPath = '/system',
    ) {
        //
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

        $names = $this->getErrorTemplateNames($exception);

        foreach ($names as $name) {
            if ($this->finder->exists($name)) {
                return new HtmlRenderer(
                    view($name, new ErrorPage($exception))
                );
            }
        }

        return new HtmlRenderer(
            <<<HTML
                <html>
                    <head>
                        <title>Error</title>
                    </head>
                    <body>
                        <div>
                            <h1>Oops!</h1>
                            <p>Something went wrong.</p>
                            <p>{$exception->getMessage()}</p>
                        </div>
                    </body>
                </html>
            HTML
        );
    }

    /**
     * Get error template names
     *
     * @param Throwable $exception
     * @return string[]
     */
    abstract protected function getErrorTemplateNames(Throwable $exception): array;

    /**
     * {@inheritDoc}
     */
    protected function shouldRender(
        ServerRequestInterface $request,
        Throwable $exception,
        ErrorSetting $setting,
    ): bool {
        $result = parent::shouldRender($request, $exception, $setting);

        if (!$result) {
            return false;
        }

        $requestPath = $request->getUri()->getPath();

        return $this->isMatchPath($requestPath, $this->systemPath);
    }

    /**
     * Judge whether to display an error page by comparing the path
     *
     * @param string $path Request path
     * @return boolean
     */
    abstract protected function isMatchPath(
        string $requestPath,
        string $systemPath,
    ): bool;
}
