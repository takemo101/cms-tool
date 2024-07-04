<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemaSettingType;
use LogicException;

/**
 * Abstract setting
 *
 * @immutable
 */
abstract class AbstractSetting
{
    /**
     * The type of schema setting
     *
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Unknown;

    /**
     * Get the type of schema setting
     *
     * @return SchemaSettingType
     * @throws LogicException If the type is unknown
     */
    public function type(): SchemaSettingType
    {
        if (static::Type->isUnknown()) {
            throw new LogicException('Type is unknown');
        }

        return static::Type;
    }

    /**
     * Create a new instance from an array
     *
     * @param array<string,mixed> $data
     * @return static
     */
    abstract public static function fromArray(array $data): static;
}
