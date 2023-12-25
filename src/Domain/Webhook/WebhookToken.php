<?php

namespace Takemo101\CmsTool\Domain\Webhook;

use Stringable;
use Takemo101\CmsTool\Domain\Shared\IdCreator;
use Takemo101\CmsTool\Domain\Shared\Trait\ValueObjectEquatable;
use Takemo101\CmsTool\Domain\Shared\ValueObject;

/**
 * @implements ValueObject<string>
 */
class WebhookToken implements ValueObject, Stringable
{
    use ValueObjectEquatable;

    /**
     * constructor
     *
     * @param string $value
     */
    public function __construct(
        private string $value,
    ) {
        assert(!empty($value), 'Webhook token must not be empty.');
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Regenerate the token.
     *
     * @return self
     */
    public function regenerate(): self
    {
        $generatedToken = self::generate();

        return $this->equals($generatedToken)
            ? $this->regenerate()
            : $generatedToken;
    }

    /**
     * Generate a new token.
     *
     * @return self
     */
    public static function generate(): self
    {
        return new self((string) IdCreator::random(32));
    }
}
