<?php

namespace CmsTool\View\Html\Transformer;

use Stringable;

final class ArrayAttrTransformer extends AbstractAttrTransformer
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
        if (!is_array($value) || empty($value)) {
            return null;
        }

        /** @var string[] */
        $values = array_map(
            fn ($attr) => $this->transformValue($attr),
            $value,
        );

        /** @var string[] */
        $values = array_filter($values, 'strlen');

        return $this->buildAttr($key, implode(' ', $values));
    }

    /**
     * Transform value to string
     *
     * @param mixed $value
     * @return string
     */
    private function transformValue(mixed $value): string
    {
        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value instanceof Stringable) {
            return (string) $value;
        }

        return '';
    }
}
