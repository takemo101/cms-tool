<?php

namespace CmsTool\Support\Dotenv;

/**
 * Replace the value corresponding to the dotenv string key.
 */
class DotenvValueReplacer
{
    /**
     * Replace the value in the content.
     *
     * @param string $content
     * @param string $key
     * @param string|int|float|bool|null $value
     * @return string
     */
    public function replace(
        string $content,
        string $key,
        string|int|float|bool|null $value,
    ): string {
        if ($value === null) {
            return $this->replaceKeyInContent(
                content: $content,
                key: $key,
                value: 'null',
            );
        }

        if (is_int($value) || is_float($value)) {
            return $this->replaceKeyInContent(
                content: $content,
                key: $key,
                value: (string) $value,
            );
        }

        if (is_bool($value)) {
            return $this->replaceKeyInContent(
                content: $content,
                key: $key,
                value: $value ? 'true' : 'false',
            );
        }

        return $this->replaceString($content, $key, $value);
    }

    /**
     * Replace the string value in the content.
     *
     * @param string $content
     * @param string $key
     * @param string $value
     * @return string
     */
    private function replaceString(
        string $content,
        string $key,
        string $value,
    ): string {
        // Replace $ with \$ to avoid error
        $escapedValue = str_replace('$', '\$', $value);

        // Wrap the value with double quotes
        $stringValue = strlen($escapedValue) > 0
            ? sprintf('"%s"', $escapedValue)
            : '';

        return $this->replaceKeyInContent(
            content: $content,
            key: $key,
            value: $stringValue,
        );
    }

    /**
     * Replace the content.
     *
     * @param string $content
     * @param string $key
     * @param string $value
     * @return string
     */
    private function replaceKeyInContent(
        string $content,
        string $key,
        string $value,
    ): string {
        $upperKey = strtoupper($key);

        $random = bin2hex(random_bytes(8));
        $replaceKey = "### ----{$random}---- ###";

        /** @var string|null */
        $replacedContent = preg_replace(
            sprintf('/%s=.*$/m', $upperKey),
            $replaceKey,
            $content,
        );

        if ($replacedContent === null) {
            return $content;
        }

        $replacedContent = str_replace(
            $replaceKey,
            sprintf('%s=%s', $key, $value),
            $replacedContent,
        );

        return $replacedContent;
    }
}
