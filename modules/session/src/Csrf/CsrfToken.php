<?php

namespace CmsTool\Session\Csrf;

use Psr\Http\Message\ServerRequestInterface;

readonly class CsrfToken
{
    /** @var string */
    public const NameKey = CsrfGuard::TokenNameKey;

    /** @var string */
    public const ValueKey = CsrfGuard::TokenValueKey;

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
     * Create a new instance of CsrfToken from a ServerRequestInterface object.
     *
     * @param ServerRequestInterface $request The server request object.
     * @return self A new instance of CsrfToken.
     */
    public static function fromServerRequest(
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
