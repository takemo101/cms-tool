<?php

namespace CmsTool\View\Twig\Extension;

use DI\Attribute\Inject;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionsExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param array<string|integer,callable-string>
     */
    public function __construct(
        #[Inject('config.view.twig.functions')]
        private readonly array $functions,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        /** @var TwigFunction[] */
        $functions = [];

        foreach ($this->functions as $method => $callable) {
            $functions[] = new TwigFunction(
                is_string($method)
                    ? $method
                    : $callable,
                fn (mixed ...$args) => call_user_func_array($callable, $args),
            );
        }

        return $functions;
    }
}
