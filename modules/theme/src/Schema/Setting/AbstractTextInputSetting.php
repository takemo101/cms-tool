<?php

namespace CmsTool\Theme\Schema\Setting;

/**
 * Text input setting
 *
 * @template T The type of input value corresponding to the schema setting type
 * @extends AbstractTextInputSetting<T>
 */
abstract class AbstractTextInputSetting extends AbstractInputSetting
{
    /**
     * constructor
     *
     * @param string $id
     * @param string $label
     * @param T|null $default
     * @param string|null $placeholder
     */
    public function __construct(
        string $id,
        string $label,
        mixed $default = null,
        public readonly ?string $placeholder = null,
    ) {
        parent::__construct(
            id: $id,
            label: $label,
            default: $default,
        );
    }
}
