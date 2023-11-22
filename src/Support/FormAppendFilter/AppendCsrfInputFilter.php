<?php

namespace Takemo101\CmsTool\Support\FormAppendFilter;

use CmsTool\Session\Csrf\CsrfToken;
use CmsTool\View\Html\Filter\FormAppendFilter;

final class AppendCsrfInputFilter implements FormAppendFilter
{
    /** @var string[] */
    public const IgnoreMethods = [
        'GET',
        'HEAD',
        'OPTIONS',
    ];

    /**
     * constructor
     *
     * @param CsrfToken|null $token
     */
    public function __construct(
        private ?CsrfToken $token = null,
    ) {
        //
    }

    /**
     * Set the token to be added to the form
     *
     * @return string|null
     */
    public function setCsrfToken(CsrfToken $token): void
    {
        $this->token = $token;
    }

    /**
     * Filter the elements to be added to the form by attributes
     *
     * @param array<string,mixed> $attributes
     * @return string|null
     */
    public function filter(array $attributes): ?string
    {
        /** @var string */
        $method = $attributes['method'] ?? 'GET';

        $token = $this->token;

        if (
            in_array($method, self::IgnoreMethods)
            || !$token
        ) {
            return null;
        }

        if ($token->isEmpty()) {
            return null;
        }

        return sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            CsrfToken::NameKey,
            $token->name,
        ) . sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            CsrfToken::ValueKey,
            $token->value,
        );
    }
}
