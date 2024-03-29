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
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->uri->getScheme();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthority()
    {
        return $this->uri->getAuthority();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getUserInfo()
    {
        return $this->uri->getUserInfo();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getHost()
    {
        return $this->uri->getHost();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPort()
    {
        return $this->uri->getPort();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPath()
    {
        return $this->uri->getPath();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->uri->getQuery();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->uri->getFragment();
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withScheme(string $scheme)
    {
        return new static($this->uri->withScheme($scheme));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withUserInfo(string $user, ?string $password = null)
    {
        return new static($this->uri->withUserInfo($user, $password));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withHost(string $host)
    {
        return new static($this->uri->withHost($host));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withPort(?int $port)
    {
        return new static($this->uri->withPort($port));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withPath(string $path)
    {
        return new static($this->uri->withPath($path));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withQuery(string $query)
    {
        return new static($this->uri->withQuery($query));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function withFragment(string $fragment)
    {
        return new static($this->uri->withFragment($fragment));
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function __toString()
    {
        return $this->uri->__toString();
    }

    /**
     * Get the current URI.
     *
     * @return string
     */
    public function getCurrent(): string
    {
        return $this->getBase() . $this->getPath();
    }

    /**
     * Get the base URI.
     *
     * @return string
     */
    public function getBase(): string
    {
        $schema = $this->getScheme();
        $authority = $this->getAuthority();

        return ($schema
            ? $schema . ':'
            : ''
        ) . ($authority ? '//' . $authority : '');
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
