<?php

namespace Takemo101\CmsTool\Domain\Theme;

use CmsTool\Theme\ThemeId;
use DomainException;

class ThemeIdException extends DomainException
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
            message: $message ?? "The theme ID is Invalid. ({$id->value()})",
        );
    }

    /**
     * Get the value of id
     */
    public function getId(): ThemeId
    {
        return $this->id;
    }

    /**
     * Create an exception for a theme ID that does not exist
     *
     * @param ThemeId $id
     * @return self
     */
    public static function notExists(ThemeId $id): self
    {
        return new self(
            id: $id,
            message: "The theme ID does not exist. ({$id->value()})",
        );
    }
}
