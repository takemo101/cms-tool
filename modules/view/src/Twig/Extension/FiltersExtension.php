<?php

namespace CmsTool\View\Twig\Extension;

use DI\Attribute\Inject;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltersExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param array<string|integer,callable-string> $filters
     */
    public function __construct(
        #[Inject('config.view.twig.filters')]
        private array $filters,
    ) {
        //
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        /** @var TwigFilter[] */
        $filters = [];

        foreach ($this->filters as $method => $callable) {
            $filters[] = new TwigFilter(
                is_string($method)
                    ? $method
                    : $callable,
                fn (mixed ...$args) => call_user_func_array($callable, $args),
            );
        }

        return $filters;
    }
}
