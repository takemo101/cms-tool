<?php

namespace CmsTool\View\Twig\Extension;

use CmsTool\View\Component\Components;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ComponentExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param Components $components
     */
    public function __construct(
        private Components $components,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'component',
                $this->components->render(...),
            ),
        ];
    }
}
