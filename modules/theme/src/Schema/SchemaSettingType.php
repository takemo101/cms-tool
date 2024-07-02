<?php

namespace CmsTool\Theme\Schema;

/**
 * Enum representing the types of input settings for a theme.
 * Input settings can include both input and display-only settings
 */
enum SchemeSettingType: string
{
    case Text = 'text'; // Single line text input
    case Textarea = 'textarea'; // Multi line text input
    case Number = 'number'; // Number input
    case Select = 'select'; // Select input
    case Checkbox = 'checkbox'; // Checkbox input
    case Color = 'color'; // Color picker
    case Header = 'header'; // Header for display purposes

    /**
     * Return whether it's an input type
     *
     * @return bool
     */
    public function isInputType(): bool
    {
        return $this !== self::Header;
    }
}
