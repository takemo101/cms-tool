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
     * constructor
     *
     * @param string $id
     * @param string $label
     * @param T|null $default
     */
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        protected readonly mixed $default = null,
    ) {
        assert(
            empty($id) === false,
            'The setting ID must not be empty',
        );

        assert(
            empty($label) === false,
            'The setting label must not be empty',
        );
    }

    /**
     * Cast the input or output value to the correct type
     *
     * @param T|null $value
     * @return T
     */
    abstract public function cast(mixed $value): mixed;

    /**
     * Default value for input or output data
     *
     * @return T|null
     */
    public function default(): mixed
    {
        $value = $this->default;

        return $value === null
            ? static::DefaultValueIfNotSet
            : $this->cast($value);
    }
}
