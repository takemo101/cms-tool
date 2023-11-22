<?php

use CmsTool\View\Twig\Extension\FiltersExtension;
use Tests\TestCase;

describe(
    'FiltersExtension',
    function () {
        test(
            'should register filters Twig functions',
            function () {
                /** @var TestCase $this */

                $filters = [
                    'array_filter',
                ];

                $extension = new FiltersExtension($filters);

                $actualFilters = $extension->getFilters();

                expect($actualFilters)->toHaveCount(count($filters));
            },
        );
    }
)->group('filters-extension', 'extension');
