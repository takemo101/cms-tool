<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\CmsTool\Support\Htmx\HtmxRedirector;
use Mockery as m;

describe('HtmxRedirector', function () {
    it('should redirect when the request is from Htmx and the response is a redirection response', function () {
        $request = m::mock(ServerRequestInterface::class);
        $handler = m::mock(RequestHandlerInterface::class);
        $response = m::mock(ResponseInterface::class);

        $request->shouldReceive('getHeaderLine')
            ->once()
            ->with('HX-Request')
            ->andReturn('true');

        $response->shouldReceive('getHeaderLine')
            ->once()
            ->with('Location')
            ->andReturn('https://example.com');

        $handler->shouldReceive('handle')
            ->once()
            ->with($request)
            ->andReturn($response);

        $response->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(302);

        $response->shouldReceive('withHeader')
            ->once()
            ->with('HX-Redirect', 'https://example.com')
            ->andReturn($response);

        $response->shouldReceive('withStatus')
            ->once()
            ->with(200)
            ->andReturn($response);

        $middleware = new HtmxRedirector();
        $result = $middleware->process($request, $handler);

        expect($result)->toBe($response);
    });

    it('should not redirect when the request is not from Htmx', function () {
        $request = m::mock(ServerRequestInterface::class);
        $handler = m::mock(RequestHandlerInterface::class);
        $response = m::mock(ResponseInterface::class);

        $request->shouldReceive('getHeaderLine')
            ->once()
            ->with('HX-Request')
            ->andReturn('');

        $handler->shouldReceive('handle')
            ->once()
            ->with($request)
            ->andReturn($response);

        $middleware = new HtmxRedirector();
        $result = $middleware->process($request, $handler);

        expect($result)->toBe($response);
    });

    it('should not redirect when the response is not a redirection response', function () {
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

        $response->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(200);

        $middleware = new HtmxRedirector();
        $result = $middleware->process($request, $handler);

        expect($result)->toBe($response);
    });
})->group('HtmxRedirector', 'support');
