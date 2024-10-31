<?php

namespace CmsTool\Cache;

readonly class MemoCacheOptions
{
    /**
     * constructor
     *
     * @param boolean|null $enabled Enable caching
     * @param int<1,max>|null $lifetime Lifetime in seconds when caching is enabled
     */
    public function __construct(
        public ?bool $enabled = null,
        public ?int $lifetime = null,
    ) {
        //
    }

    /**
     * Create options with caching enabled and a set lifetime.
     *
     * @param int<1,max> $lifetime Lifetime in seconds when caching is enabled
     * @return self
     */
    public static function createWithLifetime(int $lifetime): self
    {
        return new self(
            enabled: true,
            lifetime: $lifetime,
        );
    }
}
