<?php

namespace CmsTool\Session\Csrf;

use Slim\Csrf\Guard;
use Psr\Http\Message\ResponseFactoryInterface;
use ArrayAccess;

final class CsrfGuard extends Guard
{
    /** @var string */
    public const Prefix = 'csrf';

    /** @var string */
    public const TokenNameKey = '_csrf_name';

    /** @var string */
    public const TokenValueKey = '_csrf_value';

    /**
     * constructor
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param array<string,mixed>|ArrayAccess<string,mixed>|null $storage
     * @param callable|null $failureHandler
     * @param integer $storageLimit
     * @param integer $strength
     * @param boolean $persistentTokenMode
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        &$storage = null,
        ?callable $failureHandler = null,
        int $storageLimit = 200,
        int $strength = 16,
        bool $persistentTokenMode = false
    ) {
        parent::__construct(
            responseFactory: $responseFactory,
            prefix: static::Prefix,
            storage: $storage,
            failureHandler: $failureHandler,
            storageLimit: $storageLimit,
            strength: $strength,
            persistentTokenMode: $persistentTokenMode
        );
    }

    /**
     * @return string
     */
    public function getTokenNameKey(): string
    {
        return static::TokenNameKey;
    }

    /**
     * @return string
     */
    public function getTokenValueKey(): string
    {
        return static::TokenValueKey;
    }

    /**
     * Get token instance.
     *
     * @return CsrfToken
     */
    public function getToken(): CsrfToken
    {
        $name = $this->getTokenName();
        $value = $this->getTokenValue();

        return new CsrfToken($name, $value);
    }
}
