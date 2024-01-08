<?php

namespace CmsTool\Support\Translation;

use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultTranslator implements Translator
{
    /**
     * constructor
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(
        private TranslatorInterface $translator
    ) {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, array $replace = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans(
            id: $key,
            parameters: $replace,
            domain: $domain,
            locale: $locale,
        );
    }

    /**
     * Get the default locale being used.
     */
    public function locale(): string
    {
        return $this->translator->getLocale();
    }
}
