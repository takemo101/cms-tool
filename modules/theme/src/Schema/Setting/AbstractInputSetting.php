<?php

namespace CmsTool\Theme\Schema\Setting;

/**
 * @template T The type of input value corresponding to the schema setting type
 */
abstract class AbstractInputSetting extends AbstractSetting
{
    /**
     * Default value if not set
     *
     * @var T
     */
    public const DefaultValueIfNotSet = null;

    /**
     * @var T
     */
    public readonly mixed $default;

    /**
     * constructor
     *
     * @param string $id
     * @param string $label
     * @param T|null $default
     */
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        mixed $default = null,
    ) {
        assert(
            empty($id) === false,
            'The setting ID must not be empty',
        );

        assert(
            empty($label) === false,
            'The setting label must not be empty',
        );

        assert(
            static::DefaultValueIfNotSet !== null,
            'The default value must not be null',
        );

        $this->default = $default ?? static::DefaultValueIfNotSet;
    }
}
