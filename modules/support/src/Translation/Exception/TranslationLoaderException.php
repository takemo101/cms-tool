<?php

namespace CmsTool\Support\Translation\Exception;

use Exception;
use Throwable;

abstract class TranslationLoaderException extends Exception
{
    /**
     * constructor
     *
     * @param string $domain
     * @param string $locale
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly string $domain,
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
     * Get the domain of the translation
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
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
