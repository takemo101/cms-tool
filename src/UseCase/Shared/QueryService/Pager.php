<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

use Takemo101\CmsTool\Support\Shared\HasMethodAccessor;

/**
 * @property-read integer $offset
 */
readonly class Pager
{
    use HasMethodAccessor;

    /** @var integer */
    public const DefaultLimit = 10;

    /** @var integer */
    public int $page;

    /** @var integer */
    public int $limit;

    /**
     * constructor
     *
     * @param int $page
     * @param int $limit
     */
    public function __construct(
        int $page = 1,
        int $limit = self::DefaultLimit,
    ) {
        $this->page = $page > 0 ? $page : 1;
        $this->limit = $limit > 0 ? $limit : self::DefaultLimit;
    }

    /**
     * get offset
     *
     * @return int
     */
    public function offset(): int
    {
        return ($this->page - 1) * $this->limit;
    }
}
