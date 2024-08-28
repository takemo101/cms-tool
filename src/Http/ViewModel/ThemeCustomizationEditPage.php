<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use CmsTool\Theme\Contract\ThemeCustomizationLoader;
use CmsTool\Theme\Theme;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Domain\Shared\IdCreator;

class ThemeCustomizationEditPage extends ViewModel
{
    /**
     * constructor
     *
     * @param Theme $theme
     */
    public function __construct(
        public readonly Theme $theme,
    ) {
        //
    }

    /**
     * Get theme schema.
     *
     * @return array<integer,array<string,mixed>>
     */
    public function schema(): array
    {
        $schema = $this->theme->meta->schema->toArray();

        /** @var array<integer,array<string,mixed>> */
        $data = array_map(
            /**
             * @param array<string,mixed> $settings
             * @return array<string,mixed>
             */
            function (array $settings) {

                /** @var array<integer,array<string,mixed>> */
                $schemaSettings = $settings['settings'];

                return [
                    ...$settings,
                    'settings' => array_map(
                        /**
                         * @param array<string,mixed> $setting
                         * @return array<string,mixed>
                         */
                        function (array $setting) {
                            return [
                                'uid' => IdCreator::ulid()->generate(),
                                ...$setting,
                            ];
                        },
                        $schemaSettings,
                    ),
                ];
            },
            $schema,
        );

        return $data;
    }

    /**
     * Get theme customization data.
     *
     * @param ThemeCustomizationLoader $loader
     * @return object
     */
    public function data(
        ThemeCustomizationLoader $loader,
    ): object {
        return ImmutableArrayObject::of(
            $loader->load($this->theme),
        );
    }
}
