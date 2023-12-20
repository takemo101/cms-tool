<?php

namespace Takemo101\CmsTool\Http\Action\Theme;

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class FixedPageAction
{
    /**
     * constructor
     *
     * @param TemplateFinder $finder
     */
    public function __construct(
        private TemplateFinder $finder,
    ) {
        //
    }
    /**
     * @param ServerRequestInterface $request
     * @param string $path
     * @return View
     */
    public function __invoke(ServerRequestInterface $request, string $path): View
    {
        $directorySeparator = TemplateFinder::DirectorySeparator;
        $namespaceSeparator = TemplateFinder::NamespaceSeparator;

        // Escape the dot of the path
        $path = preg_quote(
            preg_quote(
                $path,
                $namespaceSeparator,
            ),
            $directorySeparator,
        );

        $name = "pages.fixed-page.{$path}";

        if (!$this->finder->exists($name)) {
            throw new HttpNotFoundException($request, " Fixed page not found: {$path}");
        }

        return view($name);
    }
}
