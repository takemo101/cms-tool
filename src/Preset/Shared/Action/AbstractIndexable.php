<?php

namespace Takemo101\CmsTool\Preset\Shared\Action;

use Takemo101\CmsTool\UseCase\Shared\QueryService\Pager;

abstract class AbstractIndexable
{
    /** @var string */
    public const PageKey = 'page';

    /** @var string */
    public const LimitKey = 'limit';

    /** @var string */
    public const OrderKey = 'order';

    /** @var string */
    public const QKey = 'q';

    /**
     * Get page number
     *
     * @param array<string,integer> $params
     * @return integer
     */
    protected function getPage(array $params): int
    {
        return (int) ($params[self::PageKey] ?? 1);
    }

    /**
     * Get per page number
     *
     * @param array<string,integer> $params
     * @param integer $default
     * @return integer
     */
    protected function getLimit(array $params, int $default = Pager::DefaultLimit): int
    {
        return (int) ($params[self::LimitKey] ?? $default);
    }

    /**
     * Get order
     *
     * @param array<string,string> $params
     * @param string|null $default
     * @return string|null
     */
    protected function getOrder(array $params, ?string $default): ?string
    {
        return $params[self::OrderKey] ?? $default;
    }

    /**
     * Get q
     *
     * @param array<string,string> $params
     * @return string|null
     */
    protected function getQ(array $params): ?string
    {
        return $params[self::QKey] ?? null;
    }
}
