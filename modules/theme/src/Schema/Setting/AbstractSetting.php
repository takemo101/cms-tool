<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

abstract class AbstractSetting
{
    /**
     * Get the type of schema setting
     *
     * @return SchemeSettingType
     */
    abstract public function type(): SchemeSettingType;

    /**
     * Create a new instance from an array
     *
     * @param array<string,mixed> $data
     * @return static
     */
    abstract public static function fromArray(array $data): static;
}
