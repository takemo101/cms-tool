<?php

use CmsTool\Support\AccessLog\AccessLogEntry;
use CmsTool\Support\AccessLog\PsrAccessLoggerProxy;
use Psr\Log\LoggerInterface;
use Mockery as m;

describe(
    'PsrAccessLoggerProxy',
    function () {

        beforeEach(function () {
            // Create mock objects
            $this->logger = m::mock(LoggerInterface::class);
            $this->accessLogger = new PsrAccessLoggerProxy($this->logger);
        });

        it('should write access log entry to the logger', function () {
            // Create a mock access log entry

            $entry = new AccessLogEntry(
                datetime: new DateTime(),
                ip: '127.0.0.1',
                uri: '/path/to/resource',
                method: 'GET',
                status: 200,
                userAgent: 'Mozilla/5.0',
                referer: 'http://referer.com',
            );

            // Set expectations on the logger mock
            $this->logger->shouldReceive('info')->once()->with(
                sprintf(
                    '%s - %s %s %s %s',
                    $entry->datetime->format('Y-m-d H:i:s'),
                    $entry->ip,
                    $entry->uri,
                    $entry->method,
                    $entry->status
                ),
                $entry->toArray()
            );

            // Call the write method
            $this->accessLogger->write($entry);
        });
    }
)->group('PsrAccessLoggerProxy', 'access-log');
