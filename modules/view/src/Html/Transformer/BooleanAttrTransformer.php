<?php

namespace CmsTool\View\Html\Transformer;

final class BooleanAttrTransformer extends AbstractAttrTransformer
{
    /**
     * Transform attributes
     *
     * @param string $key
     * @param mixed $value
     * @return string|null
     */
    public function transform(string $key, mixed $value): ?string
    {
        if (!is_bool($value)) {
            return null;
        }

        return $value ? $key : null;
    }
}
