<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Schema\SchemeSettingType;

/**
 * Setting to display a header on the theme settings page
 *
 * @immutable
 */
class HeaderSetting extends AbstractSetting
{
    /**
     * constructor
     *
     * @param string $title
     * @param string|null $description
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function type(): SchemeSettingType
    {
        return SchemeSettingType::Header;
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   title: string,
     *   description?: string,
     * }
     */
    public static function fromArray(array $data): static
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
        );
    }
}
