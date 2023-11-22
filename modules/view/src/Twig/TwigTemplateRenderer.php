<?php

namespace CmsTool\View\Twig;

use CmsTool\View\Contract\TemplateRenderer;
use Twig\Environment;

class TwigTemplateRenderer implements TemplateRenderer
{
    /**
     * constructor
     *
     * @param Environment $twig
     */
    public function __construct(
        private Environment $twig,
    ) {
        //
    }

    /**
     * Render the given template path.
     *
     * @param string $path
     * @param array<string,mixed> $data
     * @return string
     */
    public function renderTemplate(string $path, array $data = []): string
    {
        $template = $this->twig->load($path);

        return $template->render($data);
    }

    /**
     * Render the given template string.
     *
     * @param string $template
     * @param array<string,mixed> $data
     * @return string
     */
    public function renderString(string $template, array $data = []): string
    {
        $template = $this->twig->createTemplate($template);

        return $template->render($data);
    }
}
