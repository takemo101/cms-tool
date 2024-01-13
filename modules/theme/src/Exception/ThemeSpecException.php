<?php

namespace CmsTool\Theme\Exception;

use CmsTool\Theme\Theme;
use DomainException;
use Throwable;

class ThemeSpecException extends DomainException
{
    /**
     * constructor
     *
     * @param Theme $theme
     * @param string|null $message
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly Theme $theme,
        ?string $message = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: $message ?? "Error on theme specifications: {$theme->id->value()}",
            previous: $previous,
        );
    }

    /**
     * Get the theme
     *
     * @return Theme
     */
    public function getTheme(): Theme
    {
        return $this->theme;
    }

    /**
     * Create a new exception for when a theme cannot be deleted.
     *
     * @param Theme $theme
     * @return self
     */
    public static function cannotBeDeletedError(
        Theme $theme,
    ): self {
        return new self(
            theme: $theme,
            message: "The theme {$theme->id->value()} cannot be deleted.",
        );
    }
}
