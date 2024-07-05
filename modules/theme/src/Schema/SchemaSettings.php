<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Schema\Setting\AbstractInputSetting;
use CmsTool\Theme\Schema\Setting\AbstractSetting;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * Collection of schema settings
 *
 * @implements Arrayable<string,mixed>
 */
readonly class SchemaSettings implements Arrayable
{
    /**
     * @var AbstractSetting[]
     */
    public array $settings;

    /**
     * @param SchemaSettingId $id
     * @param string $title
     * @param AbstractSetting ...$settings
     */
    public function __construct(
        public SchemaSettingId $id,
        public string $title,
        AbstractSetting ...$settings,
    ) {
        assert(
            empty($title) === false,
            'The title must not be empty',
        );

        assert(
            empty($settings) === false,
            'The settings array must not be empty',
        );

        /**
         * @var string[]
         */
        $settingIds = [];

        foreach ($settings as $setting) {

            // Check if the setting is an instance of AbstractInputSetting
            if ($setting instanceof AbstractInputSetting) {
                assert(
                    in_array($setting->id->value(), $settingIds) === false,
                    'The setting ID must be unique',
                );

                $settingIds[] = $setting->id->value();
            }
        }

        $this->settings = $settings;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'title' => $this->title,
            'settings' => array_map(
                fn (AbstractSetting $setting) => [
                    "type" => $setting->type()->value,
                    ...$setting->toArray(),
                ],
                $this->settings,
            ),
        ];
    }
}
