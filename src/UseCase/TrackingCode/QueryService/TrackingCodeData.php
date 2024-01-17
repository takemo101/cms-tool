<?php

namespace Takemo101\CmsTool\UseCase\TrackingCode\QueryService;

readonly class TrackingCodeData
{
    /**
     * constructor
     *
     * @param string|null $head
     * @param string|null $body
     * @param string|null $footer
     */
    public function __construct(
        public ?string $head,
        public ?string $body,
        public ?string $footer,
    ) {
        //
    }
}
