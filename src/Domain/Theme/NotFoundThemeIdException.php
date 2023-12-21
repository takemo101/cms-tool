<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\ThemeId;
use DomainException;

class NotFoundThemeIdException extends DomainException
{
    /**
     * constructor
     *
     * @param ThemeId $id
     */
    public function __construct(
        private readonly ThemeId $id,
        ?string $message = null,
    ) {
        parent::__construct(
            message: $message ?? "Theme ID [{$id->value()}] not found.",
        );
    }

    /**
     * Get the value of id
     */
    public function getId(): ThemeId
    {
        return $this->id;
    }
}
