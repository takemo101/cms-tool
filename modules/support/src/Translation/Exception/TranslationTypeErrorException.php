<?php

namespace CmsTool\Support\Translation\Exception;

class TranslationTypeErrorException extends TranslatorException
{
    /**
     * Exceptions when it is not a character string as a result of translation
     *
     * @param string $key
     * @param string $locale
     * @return self
     */
    public static function notStringError(
        string $key,
        string $locale
    ): self {
        return new self(
            key: $key,
            locale: $locale,
            message: "The translation must be a string"
        );
    }
}
