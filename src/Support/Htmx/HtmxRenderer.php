<?php

namespace Takemo101\CmsTool\Support\Htmx;


use Takemo101\CmsTool\Support\Shared\AbstractResponseRenderer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * HTMX Response (defines only necessary methods)
 *
 * ref: https://htmx.org/reference/#response_headers
 */
class HtmxRenderer extends AbstractResponseRenderer
{
    /**
     * Set HX-Retarget header
     *
     * @param string $selector
     * @return static
     */
    public function setHxRetarget(string $selector): static
    {
        return $this->addHeader('HX-Retarget', $selector);
    }

    /**
     * Set HX-Reswap header
     *
     * @param string $selector
     * @param string $event
     * @return static
     */
    public function setHxReswap(string $selector): static
    {
        return $this->addHeader('HX-Reswap', $selector);
    }

    /**
     * Set header for client-side redirect using Htmx
     *
     * @param string $to
     * @return static
     */
    public function setHxRedirect(string $to): static
    {
        return $this->addHeader('HX-Redirect', $to);
    }

    /**
     * Create a response for client-side redirect using Htmx
     *
     * @return static
     */
    public static function redirect(string $to): static
    {
        return (new self())->setHxRedirect($to);
    }

    /**
     * Create a response for client-side redirect back using Htmx
     *
     * @param ServerRequestInterface $request
     * @return static
     */
    public static function back(ServerRequestInterface $request): static
    {
        return (new self())->setHxRedirect($request->getHeaderLine('referer'));
    }
}
