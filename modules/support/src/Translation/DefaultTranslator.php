<?php

namespace CmsTool\Support\Translation;

use CmsTool\Support\Translation\Exception\TranslationTypeErrorException;
use DI\Attribute\Inject;
use Illuminate\Support\Arr;

class DefaultTranslator implements Translator
{
    /**
     * constructor
     *
     * @param TranslationLoader $loader
     * @param TranslationStringReplacer $replacer
     * @param string $locale default locale
     */
    public function __construct(
        private TranslationLoader $loader,
        private TranslationStringReplacer $replacer,
        #[Inject('config.app.locale')]
        private string $locale = self::DefaultLocale,
    ) {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $key, ?string $locale = null): bool
    {
        [$domain] = $this->getDomainAndTranslation($key);

        if (!$this->loader->exists($domain)) {
            return false;
        }

        $resource = $this->loader->load($domain, $locale ?? $this->getLocale());

        return Arr::has($resource, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function translate(string $key, array $replace = [], ?string $locale = null): string
    {
        [$domain, $translation] = $this->getDomainAndTranslation($key);

        $locale ??= $this->getLocale();

        $resource = $this->loader->load($domain, $locale);

        $translated = Arr::get($resource, $translation, $translation);

        if (!is_string($translated)) {
            throw TranslationTypeErrorException::notStringError(
                key: $key,
                locale: $locale,
            );
        }

        return $this->replacer->replace(
            translation: $translated,
            replace: $replace,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return $this->locale;
    }


    /**
     * {@inheritdoc}
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Get the domain and  of the translation
     *
     * @param string $key
     * @return array{string,string}
     */
    private function getDomainAndTranslation(string $key): array
    {
        $parts = explode('.', $key);

        $domain = array_shift($parts);

        $translation = implode('.', $parts);

        return [$domain, $translation];
    }
}
