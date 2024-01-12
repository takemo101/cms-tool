<?php

namespace CmsTool\Support\Translation;

class TranslationStringReplacer
{
    /**
     * The prefix of the key to be replaced
     * :key or key is replaced
     */
    public const KeyPrefix = ':';

    /**
     * Replace the translation string
     *
     * @param string $translation
     * @param array<string,mixed> $replace
     * @return string
     */
    public function replace(
        string $translation,
        array $replace = []
    ): string {

        $result = $translation;

        foreach ($replace as $key => $value) {
            $result = str_replace(
                search: [self::KeyPrefix . $key, $key],
                replace: (string) $value, // @phpstan-ignore-line
                subject: $result
            );
        }

        return $result;
    }
}
