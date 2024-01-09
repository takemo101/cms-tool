<?php

namespace CmsTool\Support\Translation\Exception;

use Exception;
use Throwable;

abstract class TranslatorException extends Exception
{
    /**
     * constructor
     *
     * @param string $key
     * @param string $locale
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly string $key,
        private readonly string $locale,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous
        );
    }

    /**
     * Get the key of the translation
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the locale of the translation
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
