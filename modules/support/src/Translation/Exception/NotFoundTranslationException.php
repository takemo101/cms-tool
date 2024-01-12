<?php

namespace CmsTool\Support\Translation\Exception;

class NotFoundTranslationException extends TranslationLoaderException
{
    /**
     * Exceptions when the translation file is not found
     *
     * @param string $domain
     * @param string $locale
     * @return self
     */
    public static function notFoundError(
        string $domain,
        string $locale
    ): self {
        return new self(
            domain: $domain,
            locale: $locale,
            message: "Translation [{$domain}] resource for [{$locale}] not found"
        );
    }
}
