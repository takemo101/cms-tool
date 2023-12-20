<?php

namespace CmsTool\Theme\Routing;

use Exception;

class NotFoundThemePresetException extends Exception
{
    /**
     * constructor
     *
     * @param string $name
     */
    public function __construct(
        private string $name,
    ) {
        parent::__construct("Route preset {$name} not found.");
    }

    public function getName(): string
    {
        return $this->name;
    }
}
