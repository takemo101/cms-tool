<?php

namespace Takemo101\CmsTool\Domain\Tracking;


readonly class TrackingCode
{
    /**
     * constructor
     *
     * @param string $head
     * @param string $body
     * @param string $footer
     */
    public function __construct(
        public string $head,
        public string $body,
        public string $footer,
    ) {
        //
    }
}
