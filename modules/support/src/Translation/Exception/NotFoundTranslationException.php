<?php

namespace CmsTool\Support\Translation\Exception;

class NotFoundTranslationException extends TranslationLoaderException
{
    /**
     * Exceptions when the translation file is not found
     *
     * @param string $domain
     * @param string $locale
     * @return static
     */
    public static function notFoundError(
        string $domain,
        string $locale
    ): static {
        return new static(
            domain: $domain,
            locale: $locale,
            message: "Translation [{$domain}] resource for [{$locale}] not found"
        );
    }
}
