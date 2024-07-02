<?php

namespace CmsTool\Theme\Schema;

use CmsTool\Theme\Schema\Setting\AbstractInputSetting;
use CmsTool\Theme\Schema\Setting\AbstractSetting;

/**
 * Collection of schema settings
 */
readonly class SchemaSettings
{
    /**
     * @var AbstractSetting[]
     */
    public array $settings;

    /**
     * @param string $id
     * @param string $title
     * @param AbstractSetting ...$settings
     */
    public function __construct(
        public string $id,
        public string $title,
        AbstractSetting ...$settings,
    ) {
        assert(
            empty($id) === false,
            'The ID must not be empty',
        );

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
                    in_array($setting->id, $settingIds) === false,
                    'The setting ID must be unique',
                );

                $settingIds[] = $setting->id;
            }
        }

        $this->settings = $settings;
    }
}
