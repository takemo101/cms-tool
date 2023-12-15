<?php

namespace CmsTool\Theme\Exception;

use RuntimeException;

class NotFoundThemeException extends RuntimeException
{
    /**
     * constructor
     *
     * @param string $id
     */
    public function __construct(
        private readonly string $id,
    ) {
        parent::__construct("Theme not found: {$id}");
    }

    /**
     * Get the id of the theme that was not found.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
