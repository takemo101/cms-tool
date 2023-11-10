<?php

namespace CmsTool\View\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Takemo101\Chubby\Config\ConfigRepository;

class ConfigExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param ConfigRepository $config
     */
    public function __construct(
        private ConfigRepository $config,
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
                'config',
                [$this->config, 'get'],
            ),
        ];
    }
}
