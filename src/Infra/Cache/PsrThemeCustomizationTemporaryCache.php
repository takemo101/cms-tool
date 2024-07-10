<?php

namespace Takemo101\CmsTool\Infra\Cache;

use CmsTool\Theme\ThemeId;
use Psr\Cache\CacheItemPoolInterface;
use Takemo101\CmsTool\UseCase\Theme\Support\ThemeCustomizationTemporaryCache;

/**
 * Use PSR-6 Cache to temporarily store theme customization data
 */
class PsrThemeCustomizationTemporaryCache implements ThemeCustomizationTemporaryCache
{
    /**
     * constructor
     *
     * @param CacheItemPoolInterface $pool
     */
    public function __construct(
        private readonly CacheItemPoolInterface $pool
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function put(ThemeId $id, array $data): void
    {
        $item = $this->pool->getItem($this->buildCacheKey($id));

        $item->set($data);
    }

    /**
     * {@inheritDoc}
     */
    public function get(ThemeId $id): array|false
    {
        $item = $this->pool->getItem($this->buildCacheKey($id));

        if (!$item->isHit()) {
            return false;
        }

        /** @var array<string,mixed<string,mixed>> $data */
        $data = $item->get();

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(ThemeId $id): void
    {
        $this->pool->deleteItem($this->buildCacheKey($id));
    }

    /**
     * Build the cache key
     *
     * @param ThemeId $id
     * @return string
     */
    private function buildCacheKey(ThemeId $id): string
    {
        $idString = $id->__toString();

        return "theme-customization.{$idString}";
    }
}
