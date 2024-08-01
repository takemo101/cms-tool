<?php

namespace CmsTool\Support\AccessLog;

use Psr\Log\LoggerInterface;

/**
 * Psr logger proxy for AccessLogger.
 */
class PsrAccessLoggerProxy implements AccessLogger
{

    /**
     * Log sprintf format
     */
    public const Format = '%s - %s %s %s %s';

    /**
     * constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function write(AccessLogEntry $entry): void
    {
        $this->logger->info(
            sprintf(
                self::Format,
                $entry->datetime->format('Y-m-d H:i:s'),
                $entry->ip,
                $entry->uri,
                $entry->method,
                $entry->status,
            ),
            $entry->toArray()
        );
    }

    /**
     * Get the logger instance
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
