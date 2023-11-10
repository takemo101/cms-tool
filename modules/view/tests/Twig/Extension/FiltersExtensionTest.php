<?php

use CmsTool\View\Twig\Extension\FiltersExtension;
use Tests\TestCase;

describe(
    'extension-filters',
    function () {
        test(
            'Register the twig filters',
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
)->group('extension-filters');
