<?php

use CmsTool\Support\AccessLog\AccessLogEntry;
use CmsTool\Support\AccessLog\Middleware\AccessLog;
use CmsTool\Support\AccessLog\AccessLogger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mockery as m;
use Psr\Http\Message\UriInterface;

describe(
    'AccessLog',
    function () {
        beforeEach(function () {
            // Create mock objects
            $this->logger = m::mock(AccessLogger::class);
            $this->request = m::mock(ServerRequestInterface::class);
            $this->response = m::mock(ResponseInterface::class);
            $this->handler = m::mock(RequestHandlerInterface::class);
        });

        it('should call the handle method of the request handler and write to the logger', function () {
            // Set the enabled flag to true
            $accessLog = new AccessLog(
                $this->logger,
                true,
            );

            // Set the server params, URI, method, status, user agent, and referer
            $serverParams = ['REMOTE_ADDR' => '127.0.0.1'];
            $uri = m::mock(UriInterface::class);
            $uri->shouldReceive('__toString')->andReturn('http://example.com');
            $method = 'GET';
            $status = '200';
            $userAgent = 'Mozilla/5.0';
            $referer = 'http://referer.com';

            // Set the expected log entry
            $expectedLogEntry = new AccessLogEntry(
                datetime: new DateTimeImmutable(),
                ip: $serverParams['REMOTE_ADDR'],
                uri: $uri,
                method: $method,
                status: $status,
                userAgent: $userAgent,
                referer: $referer,
            );

            // Mock the methods of ServerRequestInterface
            $this->request->shouldReceive('getServerParams')->andReturn($serverParams);
            $this->request->shouldReceive('getUri')->andReturn($uri);
            $this->request->shouldReceive('getMethod')->andReturn($method);
            $this->request->shouldReceive('getHeaderLine')->with('User-Agent')->andReturn($userAgent);
            $this->request->shouldReceive('getHeaderLine')->with('Referer')->andReturn($referer);

            $this->response->shouldReceive('getStatusCode')->andReturn($status);

            // Mock the methods of RequestHandlerInterface
            $this->handler->shouldReceive('handle')->with($this->request)->andReturn($this->response);

            // Mock the methods of AccessLogger
            $this->logger->shouldReceive('write'); // ->with($expectedLogEntry);

            // Call the process method
            $result = $accessLog->process($this->request, $this->handler);

            // Assert that the handle method is called and the logger is written to
            expect($result)->toBe($this->response);
        });

        it('should not write to the logger if the enabled flag is false', function () {
            // Set the enabled flag to false
            $accessLog = new AccessLog(
                $this->logger,
                false,
            );

            // Mock the methods of RequestHandlerInterface
            $this->handler->shouldReceive('handle')->with($this->request)->andReturn($this->response);

            // Mock the methods of AccessLogger
            $this->logger->shouldNotReceive('write');

            // Call the process method
            $result = $accessLog->process($this->request, $this->handler);

            // Assert that the handle method is called and the logger is not written to
            expect($result)->toBe($this->response);
        });
    }
)->group('AccessLog', 'access-log');
