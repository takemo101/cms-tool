<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use ArrayObject;
use CmsTool\Theme\Contract\ThemeCustomizationLoader;
use CmsTool\Theme\Theme;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;
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
     * @return array<string,array<string,mixed>>
     */
    public function schema(): array
    {
        $schema = $this->theme->meta->schema->toArray();

        $data = array_map(
            fn (array $settings) => [
                ...$settings,
                'settings' => array_map(
                    fn (array $setting) => [
                        'uid' => IdCreator::ulid()->generate(),
                        ...$setting,
                    ],
                    $settings['settings'],
                ),
            ],
            $schema,
        );

        return $data;
    }

    /**
     * Get theme customization data.
     *
     * @param ThemeCustomizationLoader $loader
     * @return ArrayObject
     */
    public function data(
        ThemeCustomizationLoader $loader,
    ): ArrayObject {
        return ImmutableArrayObject::of(
            $loader->load($this->theme),
        );
    }
}
