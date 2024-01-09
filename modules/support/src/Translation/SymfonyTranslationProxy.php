<?php

namespace CmsTool\Support\Translation;

use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;

class SymfonyTranslationProxy implements TranslatorInterface
{
    public const ValidationDomain = 'validations';

    /**
     * constructor
     *
     * @param Translator $translator
     */
    public function __construct(
        private Translator $translator
    ) {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $domain ??= Translator::DefaultDomain;

        $key = "{$domain}.{$id}";

        return $this->translator->translate(
            key: $key,
            replace: $parameters,
            locale: $locale,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return $this->translator->getLocale();
    }
}
