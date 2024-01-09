<?php

namespace CmsTool\Support\Translation;

use CmsTool\Support\Translation\Exception\TranslationLoaderException;
use CmsTool\Support\Translation\Exception\TranslatorException;

interface Translator
{
    /**
     * The default domain name.
     */
    public const DefaultDomain = 'messages';

    /**
     * The default locale.
     */
    public const DefaultLocale = 'en';

    /**
     * Check if a translation exists for a given key.
     *
     * @param string $key It is a key expressed in the dot notation.
     * @param string|null $locale
     * @return boolean
     */
    public function exists(string $key, ?string $locale = null): bool;

    /**
     * Get the translation for a given key.
     *
     * @param string $key It is a key expressed in the dot notation.
     * The first part is a domain name, and the domain name is a group of keys separated by dots.
     * @param mixed[] $replace
     * @param string|null $locale The locale or null to use the default
     * @return string
     * @throws TranslationLoaderException|TranslatorException
     */
    public function translate(string $key, array $replace = [], ?string $locale = null): string;

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getLocale(): string;

    /**
     * Set the default locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void;
}
