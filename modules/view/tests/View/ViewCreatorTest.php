<?php

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\Contract\TemplateRenderer;
use CmsTool\View\View;
use CmsTool\View\ViewCreator;
use Takemo101\Chubby\Contract\Arrayable;
use Tests\TestCase;

describe(
    'ViewCreator',
    function () {
        test(
            'Create a View object',
            function () {
                /** @var TestCase $this */

                $viewCreator = new ViewCreator(
                    Mockery::mock(TemplateFinder::class),
                    Mockery::mock(TemplateRenderer::class),
                );

                $actual = $viewCreator->create('template');

                expect($actual)->toBeInstanceOf(View::class);
            },
        );

        test(
            'Rendering the created View object.',
            function () {
                /** @var TestCase $this */

                $expected = 'template';

                $finder = Mockery::mock(TemplateFinder::class);

                $finder
                    ->shouldReceive('find')
                    ->andReturn('template');

                $renderer = Mockery::mock(TemplateRenderer::class);

                $renderer
                    ->shouldReceive('renderTemplate')
                    ->andReturn($expected);

                $viewCreator = new ViewCreator(
                    $finder,
                    $renderer,
                );

                $actual = $viewCreator->create('template');

                expect($actual->render())->toBe($expected);
            },
        );

        test(
            'Rendering a template string.',
            function () {
                /** @var TestCase $this */

                $expected = 'template';

                $finder = Mockery::mock(TemplateFinder::class);

                $finder
                    ->shouldReceive('find')
                    ->andReturn('template');

                $renderer = Mockery::mock(TemplateRenderer::class);

                $renderer
                    ->shouldReceive('renderString')
                    ->andReturn($expected);

                $viewCreator = new ViewCreator(
                    $finder,
                    $renderer,
                );

                $actual = $viewCreator->createString('template');

                expect($actual)->toBe($expected);
            },
        );

        test(
            'Set shared values.',
            function () {
                /** @var TestCase $this */

                $viewCreator = new ViewCreator(
                    Mockery::mock(TemplateFinder::class),
                    Mockery::mock(TemplateRenderer::class),
                );

                $viewCreator->share('key', 'value');

                expect($viewCreator->getShared())->toBe(['key' => 'value']);
            },
        );

        test(
            'Rendering a test twig template.',
            function ($data, string $expected) {
                /** @var TestCase $this */

                /** @var TemplateFinder */
                $finder = $this->getContainer()->get(TemplateFinder::class);

                $finder->addLocation(dirname(__DIR__, 1) . '/resources/views');

                $viewCreator = new ViewCreator(
                    $finder,
                    $this->getContainer()->get(TemplateRenderer::class),
                );

                $actual = $viewCreator->create('test', $data);

                expect($actual->render())->toContain($expected);
            },
        )->with('view-creator-data');

        test(
            'Rendering a test twig template string.',
            function ($data, string $expected) {
                /** @var TestCase $this */

                /** @var TemplateFinder */
                $finder = $this->getContainer()->get(TemplateFinder::class);

                $viewCreator = new ViewCreator(
                    $finder,
                    $this->getContainer()->get(TemplateRenderer::class),
                );

                expect($viewCreator->createString(
                    '<div>{{ test }}</div>',
                    $data,
                ))->toContain($expected);
            },
        )->with('view-creator-data');

        test(
            'Rendering using shared values in Twig templates.',
            function () {
                /** @var TestCase $this */

                /** @var TemplateFinder */
                $finder = $this->getContainer()->get(TemplateFinder::class);

                $viewCreator = new ViewCreator(
                    $finder,
                    $this->getContainer()->get(TemplateRenderer::class),
                );

                $expected = 'test';

                $viewCreator->share('share', $expected);

                expect($viewCreator->createString(
                    '<div>{{ share }}</div>',
                ))->toContain($expected);
            },
        );
    }
)->group('view-creator', 'view');


dataset(
    'view-creator-data',
    [
        [['test' => 'test'], 'test'],
        [
            new class implements Arrayable
            {
                public function toArray(): array
                {
                    return ['test' => 'hoge'];
                }
            },
            'hoge'
        ],
    ],
);
