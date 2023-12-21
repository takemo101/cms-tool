<?php

namespace CmsTool\Theme\Exception;

use CmsTool\Theme\ThemeId;
use RuntimeException;

class NotFoundThemeException extends RuntimeException
{
    /**
     * constructor
     *
     * @param ThemeId $id
     */
    public function __construct(
        private readonly ThemeId $id,
    ) {
        parent::__construct("Theme not found: {$id}");
    }

    /**
     * Get the id of the theme that was not found.
     *
     * @return ThemeId
     */
    public function getId(): ThemeId
    {
        return $this->id;
    }
}
