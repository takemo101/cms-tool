<?php

namespace CmsTool\Support\Translation;

use CmsTool\Support\Translation\Exception\TranslationConversionException;

interface TranslationSaver
{
    /**
     * Save the translation data
     *
     * @param string $domain
     * @param string $locale
     * @param array<string,mixed> $data
     * @return void
     * @throws TranslationConversionException
     */
    public function save(
        string $domain,
        string $locale = Translator::DefaultLocale,
        array $data = [],
    ): void;
}
