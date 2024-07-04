<?php

namespace CmsTool\Theme\Schema\Setting;

use Takemo101\Chubby\Contract\Arrayable;

/**
 * @implements Arrayable<string,mixed>
 */
readonly class SelectOption implements Arrayable
{
    /**
     * constructor
     *
     * @param string $value
     * @param string $label
     */
    public function __construct(
        public string $value,
        public string $label,
    ) {
        assert(
            empty($label) === false,
            'The option label must not be empty',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label,
        ];
    }
}
