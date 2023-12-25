<?php

namespace Takemo101\CmsTool\Preset\Shared\Exception;

use Exception;

class NotFoundThemeTemplateException extends Exception
{
    /**
     * constructor
     *
     * @param string[] $names
     */
    public function __construct(
        private array $names,
    ) {
        $joinedNames = implode(', ', $names);

        parent::__construct("Not found theme template: {$joinedNames}");
    }

    /**
     * Get template names
     *
     * @return string[]
     */
    public function getTemplateNames(): array
    {
        return $this->names;
    }
}
