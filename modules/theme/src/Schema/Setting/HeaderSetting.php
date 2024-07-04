<?php

namespace CmsTool\Theme\Schema\Setting;

use CmsTool\Theme\Exception\ArrayKeyMissingException;
use CmsTool\Theme\Schema\SchemaSettingType;

/**
 * Setting to display a header on the theme settings page
 *
 * @immutable
 */
class HeaderSetting extends AbstractSetting
{
    /**
     * @var SchemaSettingType
     */
    public const Type = SchemaSettingType::Header;

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
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array{
     *   title: string,
     *   description?: string,
     * } $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            title: $data['title'] ?? ArrayKeyMissingException::throw('title'),
            description: $data['description'] ?? null,
        );
    }
}
