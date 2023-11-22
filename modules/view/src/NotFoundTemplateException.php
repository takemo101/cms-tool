<?php

namespace CmsTool\View;

use RuntimeException;

class NotFoundTemplateException extends RuntimeException
{
    /**
     * constructor
     *
     * @param string $name
     */
    public function __construct(
        private readonly string $name,
    ) {
        parent::__construct("Template not found: {$name}");
    }

    /**
     * Get the name of the template that was not found.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
