<?php

namespace CmsTool\Support\Translation;

use CmsTool\Support\Translation\Exception\NotFoundTranslationException;
use CmsTool\Support\Translation\Exception\TranslationConversionException;
use CmsTool\Support\Translation\Exception\TranslationResourceException;

/**
 * @template ResourceType
 */
interface TranslationLoader
{
    /**
     * Load the translation and return the data in array format
     *
     * @param string $domain
     * @param string $locale
     * @return array<string,mixed>
     * @throws TranslationConversionException|NotFoundTranslationException
     */
    public function load(string $domain, string $locale = Translator::DefaultLocale): array;

    /**
     * Check if the translation exists
     *
     * @param string $domain
     * @return boolean
     */
    public function exists(string $domain, string $locale = Translator::DefaultLocale): bool;

    /**
     * Add the resource of the translation
     *
     * @param ResourceType $resource
     * @return void
     * @throws TranslationResourceException
     */
    public function addResource($resource): void;
}
