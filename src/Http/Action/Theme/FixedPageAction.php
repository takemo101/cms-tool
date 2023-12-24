<?php

namespace Takemo101\CmsTool\Http\Action\Theme;

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\View;
use DI\Attribute\Inject;
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
        #[Inject('config.system.route')]
        private string $systemPath = '/system',
    ) {
        //
    }
    /**
     * @param ServerRequestInterface $request
     * @param string $path
     * @return View
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request, string $path): View
    {
        // 404 if it matches the route path of the system
        if (str_starts_with("/{$path}", $this->systemPath)) {
            throw new HttpNotFoundException($request);
        }

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
