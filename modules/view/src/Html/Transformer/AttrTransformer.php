<?php

namespace CmsTool\View\Html\Transformer;

interface AttrTransformer
{
    /**
     * Transform attributes
     *
     * @param string $key
     * @param mixed $value
     * @return string|null
     */
    public function transform(string $key, mixed $value): ?string;
}
