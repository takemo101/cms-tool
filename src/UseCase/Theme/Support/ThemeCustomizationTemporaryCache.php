<?php

namespace Takemo101\CmsTool\UseCase\Theme\Support;

use CmsTool\Theme\ThemeId;

/**
 * Save and retrieve temporary customization data for a theme.
 */
interface ThemeCustomizationTemporaryCache
{
    /**
     * Save the temporary customization data.
     *
     * @param ThemeId $id
     * @param array<string,array<string,mixed>> $data
     * @return void
     */
    public function put(ThemeId $id, array $data): void;

    /**
     * Get the temporary customization data.
     *
     * @param ThemeId $id
     * @return array<string,array<string,mixed>>|false
     */
    public function get(ThemeId $id): array|false;

    /**
     * Clear the temporary customization data.
     *
     * @param ThemeId $id
     * @return void
     */
    public function clear(ThemeId $id): void;
}
