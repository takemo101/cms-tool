<?php

use CmsTool\View\Html\ElementBuilder;
use CmsTool\View\Html\Filter\FormAppendFilters;
use CmsTool\View\Html\FormBuilder;
use CmsTool\View\Twig\Extension\FormExtension;
use Slim\Interfaces\RouteParserInterface;

describe(
    'FiltersExtension',
    function () {

        it(
            'should register form_open and form_close Twig functions',
            function () {
                $routeParser = Mockery::mock(RouteParserInterface::class);
                $builder = new FormBuilder(
                    $routeParser,
                    new ElementBuilder(),
                    new FormAppendFilters()
                );
                $extension = new FormExtension($builder);

                $functions = $extension->getFunctions();

                expect($functions)->toHaveCount(2);

                $formOpenFunction = $functions[0];
                expect($formOpenFunction->getName())->toBe('form_open');
                expect($formOpenFunction->getCallable())->toBeInstanceOf(Closure::class);

                $formCloseFunction = $functions[1];
                expect($formCloseFunction->getName())->toBe('form_close');
                expect($formCloseFunction->getCallable())->toBeInstanceOf(Closure::class);
            }
        );
    }
)->group('form-extension', 'extension');
