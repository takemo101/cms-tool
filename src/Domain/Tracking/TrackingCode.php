<?php

namespace Takemo101\CmsTool\Domain\Tracking;

readonly class TrackingCode
{
    /**
     * constructor
     *
     * @param string|null $head
     * @param string|null $body
     * @param string|null $footer
     */
    public function __construct(
        public ?string $head = null,
        public ?string $body = null,
        public ?string $footer = null,
    ) {
        //
    }
}
