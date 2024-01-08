<?php

namespace CmsTool\Support\Translation;

use Symfony\Contracts\Translation\TranslatorInterface;

interface Translator
{
    /**
     * Get the translation for a given key.
     *
     * @param string $key
     * @param mixed[] $replace
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     * @return string
     */
    public function get(string $key, array $replace = [], ?string $domain = null, ?string $locale = null): string;

    /**
     * Get the default locale being used.
     */
    public function locale(): string;
}
