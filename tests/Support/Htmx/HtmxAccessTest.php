<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;
use Takemo101\CmsTool\Support\Htmx\HtmxAccess;
use Mockery as m;

describe(
    'HtmxAccess',
    function () {
        it('should throw an HttpException when the request is not from Htmx', function () {
            $request = m::mock(ServerRequestInterface::class);
            $handler = m::mock(RequestHandlerInterface::class);

            $request->shouldReceive('getHeaderLine')
                ->once()
                ->with('HX-Request')
                ->andReturn('');

            $middleware = new HtmxAccess();

            expect(fn () => $middleware->process($request, $handler))
                ->toThrow(HttpException::class);
        });

        it('should delegate to the request handler when the request is from Htmx', function () {
            $request = m::mock(ServerRequestInterface::class);
            $handler = m::mock(RequestHandlerInterface::class);
            $response = m::mock(ResponseInterface::class);

            $request->shouldReceive('getHeaderLine')
                ->once()
                ->with('HX-Request')
                ->andReturn('true');

            $handler->shouldReceive('handle')
                ->once()
                ->with($request)
                ->andReturn($response);

            $middleware = new HtmxAccess();

            $result = $middleware->process($request, $handler);

            expect($result)->toBe($response);
        });
    }
)->group('HtmxAccess', 'support');
