<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemaSettingType;
use LogicException;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * Abstract setting
 *
 * @implements Arrayable<string,mixed>
 * @immutable
 */
abstract class AbstractSetting implements Arrayable
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
     * @return self
     */
    abstract public static function fromArray(array $data): self;
}
