<?php

use CmsTool\View\Html\Filter\FormAppendFilters;
use CmsTool\View\Html\Filter\FormAppendFilter;

describe(
    'FormAppendFilters::addFilter()',
    function () {

        test(
            'should add a filter to the collection',
            function () {
                $filter1 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return 'filter1';
                    }
                };

                $filter2 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return 'filter2';
                    }
                };

                $formAppendFilters = new FormAppendFilters($filter1);
                $formAppendFilters->addFilter($filter2);

                $result = $formAppendFilters->filter([]);

                expect($result)->toBe('filter1filter2');
            }
        );
    }
)->group('form-append-filters', 'filter');

describe(
    'FormAppendFilters::filter()',
    function () {

        test(
            'should return null if no filters match',
            function () {
                $formAppendFilters = new FormAppendFilters();

                $result = $formAppendFilters->filter([]);

                expect($result)->toBeNull();
            }
        );

        test(
            'should return the result of the first matching filter',
            function () {
                $filter1 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return null;
                    }
                };

                $filter2 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return 'filter2';
                    }
                };

                $formAppendFilters = new FormAppendFilters($filter1, $filter2);

                $result = $formAppendFilters->filter([]);

                expect($result)->toBe('filter2');
            }
        );

        test(
            'should concatenate the results of all matching filters',
            function () {
                $filter1 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return 'filter1';
                    }
                };

                $filter2 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return 'filter2';
                    }
                };

                $filter3 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return null;
                    }
                };

                $filter4 = new class implements FormAppendFilter
                {
                    public function filter(array $attributes): ?string
                    {
                        return 'filter4';
                    }
                };

                $formAppendFilters = new FormAppendFilters($filter1, $filter2, $filter3, $filter4);

                $result = $formAppendFilters->filter([]);

                expect($result)->toBe('filter1filter2filter4');
            }
        );
    }
)->group('form-append-filters', 'filter');
