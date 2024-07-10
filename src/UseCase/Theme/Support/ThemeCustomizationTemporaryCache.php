<?php

namespace Takemo101\CmsTool\UseCase\Theme\Support;

use CmsTool\Theme\ThemeId;

/**
 * Save and retrieve temporary customization data for a theme.
 */
interface ThemeCustomizationTemporaryCache
{
    public function put(ThemeId $id, array $data): void;

    public function get(ThemeId $id): array|false;

    public function clear(ThemeId $id): void;
}
