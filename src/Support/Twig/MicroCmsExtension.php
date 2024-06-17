<?php

namespace Takemo101\CmsTool\Support\Twig;

use Takemo101\CmsTool\Infra\MicroCms\MicroCmsImageApiQueryBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MicroCmsExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param MicroCmsImageApiQueryBuilder $builder
     */
    public function __construct(
        private MicroCmsImageApiQueryBuilder $builder,
    ) {
        //
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('microcms_img', [$this->builder, 'build']),
        ];
    }
}
