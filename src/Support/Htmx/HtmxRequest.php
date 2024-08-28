<?php

namespace Takemo101\CmsTool\Support\Htmx;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Wrapper class to retrieve information related to Htmx from the request.
 */
readonly class HtmxRequest
{
    /**
     * Constructor.
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(
        private ServerRequestInterface $request,
    ) {
        //
    }

    /**
     * Determines if the request is from Htmx.
     *
     * @return bool
     */
    public function isHtmx(): bool
    {
        $header = $this->request->getHeaderLine('HX-Request');

        return filter_var($header, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Determines if the request is from Htmx.
     *
     * Returns false if the URL does not exist.
     *
     * @return string|false
     */
    public function getCurrentUrl(): string|false
    {
        $header = $this->request->getHeaderLine('HX-Current-URL') ?:
            $this->request->getHeaderLine('HX-Current-Url');

        return $header ?: false;
    }
}
