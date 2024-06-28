<?php

namespace CmsTool\Theme\Schema;

/**
 * Input types used in schema settings to customize the theme
 */
enum SchemeInputType: string
{
    case SingleText = 'text'; // Single line text input
    case MultiText = 'multi-text'; // Multi line text input
    case Number = 'number'; // Number input
    case SingleSelect = 'select'; // Single select input
    case MultiSelect = 'multi-select'; // Multi select input
    case OnOff = 'on-off'; // On/Off switch
    case Color = 'color'; // Color picker
}
