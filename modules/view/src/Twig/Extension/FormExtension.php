<?php

namespace CmsTool\View\Twig\Extension;

use CmsTool\View\Html\FormBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param FormBuilder $builder
     */
    public function __construct(
        private FormBuilder $builder,
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
                'form_open',
                [$this->builder, 'buildOpen'],
                [
                    'is_safe' => ['html'],
                ],
            ),
            new TwigFunction(
                'form_close',
                [$this->builder, 'buildClose'],
                [
                    'is_safe' => ['html'],
                ],
            ),
        ];
    }
}
