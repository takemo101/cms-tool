<?php

namespace CmsTool\Theme;

use DI\Attribute\Inject;

class DefaultThemeId extends ThemeId
{
    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        #[Inject('config.theme.default')]
        string $value,
    ) {
        parent::__construct($value);
    }
}
