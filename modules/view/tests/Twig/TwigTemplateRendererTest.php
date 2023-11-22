
<?php

use CmsTool\View\HtmlObject;
use CmsTool\View\Twig\TwigTemplateRenderer;
use Takemo101\Chubby\Contract\Renderable;
use Tests\TestCase;
use Twig\Environment;

describe(
    'TwigTemplateRenderer',
    function () {
        test(
            'Rendering from a template path',
            function ($data) {
                /** @var TestCase $this */

                $twig = $this->getContainer()->get(Environment::class);

                $renderer = new TwigTemplateRenderer($twig);

                $content = $renderer->renderTemplate(dirname(__DIR__, 1) . '/resources/views/test.twig', ['test' => $data]);

                expect($content)->toContain((string)$data);
            },
        )->with('template-renderer-data');

        test(
            'Rendering from a template string',
            function ($data) {
                /** @var TestCase $this */

                $twig = $this->getContainer()->get(Environment::class);

                $renderer = new TwigTemplateRenderer($twig);

                $content = $renderer->renderString('<div>{{ test }}</div>', ['test' => $data]);

                expect($content)->toContain((string)$data);
            },
        )->with('template-renderer-data');
    }
)->group('twig-template-renderer', 'twig');

dataset(
    'template-renderer-data',
    [
        'test',
        new HtmlObject('<p>test<p>'),
        new class implements Renderable
        {
            /**
             * Convert the object to its string representation.
             *
             * @return string
             */
            public function render(): string
            {
                return '<div>test</div>';
            }

            public function __toString()
            {
                return $this->render();
            }
        },
    ]
);
