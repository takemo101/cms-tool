<?php

namespace CmsTool\Session\Csrf;

use Psr\Http\Message\ServerRequestInterface;

readonly class CsrfToken
{
    /** @var string */
    public const NameKey = CsrfGuard::TokenNameKey;

    /** @var string */
    public const ValueKey = CsrfGuard::TokenValueKey;

    /** @var string */
    public const HeaderNameKey = CsrfGuard::HeaderTokenNameKey;

    /** @var string */
    public const HeaderValueKey = CsrfGuard::HeaderTokenValueKey;

    /**
     * constructor
     *
     * @param string|null $name
     * @param string|null $value
     */
    public function __construct(
        public ?string $name = null,
        public ?string $value = null,
    ) {
        //
    }

    /**
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->name) || empty($this->value);
    }

    /**
     * @return string
     */
    public function getTokenNameKey(): string
    {
        return self::NameKey;
    }

    /**
     * @return string
     */
    public function getTokenValueKey(): string
    {
        return self::ValueKey;
    }

    /**
     * @return string
     */
    public function getHeaderNameKey(): string
    {
        return self::HeaderNameKey;
    }

    /**
     * @return string
     */
    public function getHeaderValueKey(): string
    {
        return self::HeaderValueKey;
    }

    /**
     * Create a new instance of CsrfToken from a ServerRequestInterface object.
     *
     * @param ServerRequestInterface $request The server request object.
     * @return self A new instance of CsrfToken.
     */
    public static function fromRequest(
        ServerRequestInterface $request,
    ): self {
        /** @var string|null */
        $name = $request->getAttribute(self::NameKey);
        /** @var string|null */
        $value = $request->getAttribute(self::ValueKey);

        return new self(
            $name,
            $value,
        );
    }
}
