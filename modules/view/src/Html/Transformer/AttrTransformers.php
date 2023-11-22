<?php

namespace CmsTool\View\Html\Transformer;

use Stringable;

final class AttrTransformers extends AbstractAttrTransformer
{
    /**
     * @var AttrTransformer[]
     */
    private array $transformers = [];

    /**
     * constructor
     *
     * @param AttrTransformer ...$transformers
     */
    public function __construct(AttrTransformer ...$transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * Adds an attribute transformer to the collection.
     *
     * @param AttrTransformer ...$transformers The transformer to add.
     * @return self
     */
    public function addTransformer(AttrTransformer ...$transformers): self
    {
        $this->transformers = [
            ...$this->transformers,
            ...$transformers,
        ];

        return $this;
    }

    /**
     * Transform attributes
     *
     * @param string $key
     * @param mixed $value
     * @return string|null
     */
    public function transform(string $key, mixed $value): ?string
    {
        if (is_null($value)) {
            return $key;
        }

        if (is_string($value) || is_numeric($value) || ($value instanceof Stringable)) {
            return $this->buildAttr($key, (string) $value);
        }

        foreach ($this->transformers as $transformer) {
            if ($attr = $transformer->transform($key, $value)) {
                return $attr;
            }
        }

        return null;
    }
}
