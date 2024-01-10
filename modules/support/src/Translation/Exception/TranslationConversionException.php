<?php

namespace CmsTool\Support\Translation\Exception;

use Throwable;

class TranslationConversionException extends TranslationLoaderException
{
    /**
     * Exception thrown when a translation conversion error occurs.
     *
     * @param string $domain
     * @param string $locale
     * @param Throwable|null $previous
     * @return self
     */
    public static function decodeError(
        string $domain,
        string $locale,
        ?Throwable $previous = null,
    ): self {
        return new self(
            domain: $domain,
            locale: $locale,
            message: sprintf('The translation "%s" could not be decoded.', $domain),
            previous: $previous,
        );
    }

    /**
     * Exception thrown when a translation conversion error occurs.
     *
     * @param string $domain
     * @param string $locale
     * @param Throwable|null $previous
     * @return self
     */
    public static function encodeError(
        string $domain,
        string $locale,
        ?Throwable $previous = null,
    ): self {
        return new self(
            domain: $domain,
            locale: $locale,
            message: sprintf('The translation "%s" could not be encoded.', $domain),
            previous: $previous,
        );
    }
}
