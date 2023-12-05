<?php

namespace Takemo101\CmsTool\Domain\Publish;

interface SitePublishRepository
{
    /**
     * @return boolean
     */
    public function isPublished(): bool;

    /**
     * Save the publish status
     *
     * @param boolean $published
     * @return void
     */
    public function save(bool $published): void;
}
