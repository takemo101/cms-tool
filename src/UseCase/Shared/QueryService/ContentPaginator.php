<?php

namespace Takemo101\CmsTool\UseCase\Shared\QueryService;

use Takemo101\CmsTool\Support\Shared\HasMethodAccessor;

/**
 * @property-read boolean $onFirstPage
 * @property-read boolean $onLastPage
 * @property-read boolean $hasMorePages
 * @property-read boolean $hasPages
 * @property-read integer $nextPage
 * @property-read integer $previousPage
 * @property-read integer $offset
 * @property-read integer $firstPage
 * @property-read integer $lastPage
 */
readonly class ContentPaginator
{
    use HasMethodAccessor;

    /**
     * constructor
     *
     * @param integer $totalCount
     * @param integer $totalPage
     * @param integer $currentPage
     * @param integer $perPage
     */
    public function __construct(
        public int $totalCount,
        public int $totalPage,
        public int $currentPage,
        public int $perPage,
    ) {
        //
    }

    /**
     * @return boolean
     */
    public function onFirstPage(): bool
    {
        return $this->currentPage === 1;
    }

    /**
     * @return boolean
     */
    public function onLastPage(): bool
    {
        return $this->currentPage === $this->totalPage;
    }

    /**
     * @return boolean
     */
    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->totalPage;
    }

    /**
     * @return boolean
     */
    public function hasPages(): bool
    {
        return $this->totalPage > 1;
    }

    /**
     * @return integer
     */
    public function nextPage(): int
    {
        return $this->currentPage + 1;
    }

    /**
     * @return integer
     */
    public function previousPage(): int
    {
        return $this->currentPage - 1;
    }

    /**
     * @return integer
     */
    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    /**
     * @return integer
     */
    public function firstPage(): int
    {
        return 1;
    }

    /**
     * @return integer
     */
    public function lastPage(): int
    {
        return $this->totalPage;
    }
}
