<?php

namespace CmsTool\Support\AccessLog;

use DateTimeInterface;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * Access log entry data.
 *
 * @implements Arrayable<string,string>
 * @immutable
 */
readonly class AccessLogEntry implements Arrayable
{
    /**
     * constructor
     *
     * @param DateTimeInterface $datetime Access date and time
     * @param string $ip
     * @param string $uri
     * @param string $method
     * @param string $status
     * @param string $userAgent
     * @param string $referer
     */
    public function __construct(
        public DateTimeInterface $datetime,
        public string $ip,
        public string $uri,
        public string $method,
        public string $status,
        public string $userAgent,
        public string $referer,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'ip' => $this->ip,
            'uri' => $this->uri,
            'method' => $this->method,
            'status' => $this->status,
            'user_agent' => $this->userAgent,
            'referer' => $this->referer,
        ];
    }
}
