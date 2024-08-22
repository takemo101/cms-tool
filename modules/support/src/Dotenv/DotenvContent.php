<?php

namespace CmsTool\Support\Dotenv;

/**
 * ドットエンブファイルの内容を表すValueObject.
 *
 * @immutable
 */
readonly class DotenvContent
{
    /**
     * @var DotenvValueReplacer
     */
    private readonly DotenvValueReplacer $replacer;

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private string $value = '',
    ) {
        $this->replacer = new DotenvValueReplacer();
    }

    /**
     * Replace the value of the given key.
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function replace(
        string $key,
        string|int|float|bool|null $value,
    ): self {
        assert(
            empty($key) === false,
            'The key must not be empty.',
        );

        return new self(
            $this->replacer->replace(
                content: $this->value,
                key: $key,
                value: $value,
            ),
        );
    }

    /**
     * Get the content value.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
