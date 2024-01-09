<?php

namespace CmsTool\Support\Translation;

class TranslationStringReplacer
{
    public const KeyPrefix = ':';

    /**
     * Replace the translation string
     *
     * @param string $translation
     * @param array<string,string> $replace
     * @return string
     */
    public function replace(
        string $translation,
        array $replace = []
    ): string {

        $result = $translation;

        foreach ($replace as $key => $value) {
            $result = str_replace(
                search: [$key, self::KeyPrefix . $key],
                replace: $value,
                subject: $result
            );
        }

        return $result;
    }
}
