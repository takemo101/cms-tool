<?php

namespace Takemo101\CmsTool\Support\Twig;

use CmsTool\Support\Translation\Translator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslationExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param Translator $translator
     */
    public function __construct(
        private Translator $translator,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('__', [$this->translator, 'translate']),
            new TwigFunction('t', [$this->translator, 'translate']),
        ];
    }
}
