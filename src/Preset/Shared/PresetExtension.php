<?php

namespace Takemo101\CmsTool\Preset\Shared;

use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderTitlesCreator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PresetExtension extends AbstractExtension
{
    /**
     * constructor
     */
    public function __construct(
        private readonly HeaderTitlesCreator $headerTitlesCreator,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('content_titles', $this->headerTitlesCreator->create(...)),
        ];
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('content_titles', $this->headerTitlesCreator->create(...)),
        ];
    }
}
