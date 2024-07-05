<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemaSettingId;

/**
 * Text input setting
 *
 * @template T The type of input value corresponding to the schema setting type
 * @extends AbstractInputSetting<T>
 */
abstract class AbstractTextInputSetting extends AbstractInputSetting
{
    /**
     * constructor
     *
     * @param SchemaSettingId $id
     * @param string $label
     * @param T|null $default
     * @param string|null $placeholder
     */
    public function __construct(
        SchemaSettingId $id,
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
