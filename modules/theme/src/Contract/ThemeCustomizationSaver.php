<?php

namespace CmsTool\Theme\Contract;

use CmsTool\Theme\Exception\ThemeSaveException;
use CmsTool\Theme\Theme;

interface ThemeCustomizationSaver
{
    /**
     * Save theme customization data.
     *
     * @param Theme $theme
     * @param array<string,array<string,mixed>> $data
     * @return void
     * @throws ThemeSaveException
     */
    public function save(Theme $theme, array $data): void;
}
