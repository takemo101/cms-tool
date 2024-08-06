<?php

namespace CmsTool\Theme\Schema\Setting;

/**
 * Format types for text input forms.
 */
enum TextInputFormat: string
{
    case Text = 'text';
    case Email = 'email';
    case Url = 'url';

    /**
     * Default value when no format is specified in the schema settings.
     *
     * @return self
     */
    public static function default(): self
    {
        return self::Text;
    }
}
