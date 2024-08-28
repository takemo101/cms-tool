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
     * @return self
     */
    public function setHxRetarget(string $selector): self
    {
        return $this->addHeader('HX-Retarget', $selector);
    }

    /**
     * Set HX-Reswap header
     *
     * @param string $selector
     * @return self
     */
    public function setHxReswap(string $selector): self
    {
        return $this->addHeader('HX-Reswap', $selector);
    }

    /**
     * Set header for client-side redirect using Htmx
     *
     * @param string $to
     * @return self
     */
    public function setHxRedirect(string $to): self
    {
        return $this->addHeader('HX-Redirect', $to);
    }

    /**
     * Create a response for client-side redirect using Htmx
     *
     * @param string $to
     * @return self
     */
    public static function redirect(string $to): self
    {
        return (new self())->setHxRedirect($to);
    }

    /**
     * Create a response for client-side redirect back using Htmx
     *
     * @param ServerRequestInterface $request
     * @return self
     */
    public static function back(ServerRequestInterface $request): self
    {
        return (new self())->setHxRedirect($request->getHeaderLine('referer'));
    }
}
