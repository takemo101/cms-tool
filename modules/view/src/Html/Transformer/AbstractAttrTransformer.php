<?php

namespace CmsTool\View\Html\Transformer;

abstract class AbstractAttrTransformer implements AttrTransformer
{
    /**
     * Build attribute string
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    protected function buildAttr(string $key, string $value): string
    {
        return "{$key}=\"{$value}\"";
    }
}
