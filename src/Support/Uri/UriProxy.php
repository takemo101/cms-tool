<?php

namespace Takemo101\CmsTool\Support\Uri;

use Nyholm\Psr7\Uri;
use Psr\Http\Message\UriInterface;
use Stringable;

class UriProxy implements UriInterface, Stringable
{
    /**
     * constructor
     *
     * @param UriInterface $uri
     */
    final public function __construct(
        private UriInterface $uri,
    ) {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme()
    {
        return $this->uri->getScheme();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority()
    {
        return $this->uri->getAuthority();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo()
    {
        return $this->uri->getUserInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->uri->getHost();
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->uri->getPort();
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->uri->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->uri->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment()
    {
        return $this->uri->getFragment();
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme(string $scheme)
    {
        return new static($this->uri->withScheme($scheme));
    }

    /**
     * {@inheritdoc}
     */
    public function withUserInfo(string $user, ?string $password = null)
    {
        return new static($this->uri->withUserInfo($user, $password));
    }

    /**
     * {@inheritdoc}
     */
    public function withHost(string $host)
    {
        return new static($this->uri->withHost($host));
    }

    /**
     * {@inheritdoc}
     */
    public function withPort(?int $port)
    {
        return new static($this->uri->withPort($port));
    }

    /**
     * {@inheritdoc}
     */
    public function withPath(string $path)
    {
        return new static($this->uri->withPath($path));
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(string $query)
    {
        return new static($this->uri->withQuery($query));
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment(string $fragment)
    {
        return new static($this->uri->withFragment($fragment));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->uri->__toString();
    }

    /**
     * Replace the URI.
     *
     * @param UriInterface $uri
     * @return void
     */
    public function replace(UriInterface $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Create a new instance from string.
     *
     * @param string $uri
     * @return static
     */
    public static function fromString(string $uri): static
    {
        return new static(new Uri($uri));
    }
}
