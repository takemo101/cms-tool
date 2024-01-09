<?php

namespace CmsTool\Support\Translation\Exception;

class TranslationTypeErrorException extends TranslatorException
{
    /**
     * Exceptions when it is not a character string as a result of translation
     *
     * @param string $key
     * @param string $locale
     * @return static
     */
    public static function notStringError(
        string $key,
        string $locale
    ): static {
        return new static(
            key: $key,
            locale: $locale,
            message: "The translation must be a string"
        );
    }
}
