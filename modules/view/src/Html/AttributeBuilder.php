<?php

namespace CmsTool\View\Html;

use CmsTool\View\Html\Transformer\AttrTransformer;
use CmsTool\View\Html\Transformer\AttrTransformers;
use DI\Attribute\Inject;

final class AttributeBuilder
{
    /**
     * constructor
     *
     * @param AttrTransformer $transformer
     */
    public function __construct(
        #[Inject(AttrTransformers::class)]
        private readonly AttrTransformer $transformer = new AttrTransformers(),
    ) {
        //
    }

    /**
     * Build attributes
     *
     * @param array<string,mixed> $attributes
     * @return string
     */
    public function build(array $attributes): string
    {
        /** @var array<integer,string|null> */
        $result = [];

        foreach ($attributes as $key => $value) {
            $result[] = $this->transformer->transform(
                strtolower($key),
                $value,
            );
        }

        /** @var string[] */
        $filtered = array_filter($result, fn (?string $attr) => !empty($attr));

        return implode(' ', $filtered);
    }
}
