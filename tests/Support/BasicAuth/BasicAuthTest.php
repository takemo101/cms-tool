<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuth;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthorizedException;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthenticator;
use Takemo101\CmsTool\Support\BasicAuth\BasicAuthHeaderParser;
use Mockery as m;

describe(
    'BasicAuth',
    function () {

        it('should handle request when enabled is false', function () {
            // Create mocks for dependencies
            $authenticator = m::mock(BasicAuthenticator::class);
            $parser = m::mock(BasicAuthHeaderParser::class);
            $request = m::mock(ServerRequestInterface::class);
            $handler = m::mock(RequestHandlerInterface::class);
            $response = m::mock(ResponseInterface::class);

            // Set up expectations
            $request->shouldReceive('getAttribute')
                ->with(BasicAuth::BasicAuthedKey, false)
                ->andReturn(false);
            $handler->shouldReceive('handle')
                ->with($request)
                ->andReturn($response);

            // Create an instance of BasicAuth with enabled set to false
            $basicAuth = new BasicAuth($authenticator, $parser, 'Web', false);

            // Call the process method
            $result = $basicAuth->process($request, $handler);

            // Assert that the result is the same as the response from the handler
            expect($result)->toBe($response);
        });

        it('should handle request when already authenticated', function () {
            // Create mocks for dependencies
            $authenticator = m::mock(BasicAuthenticator::class);
            $parser = m::mock(BasicAuthHeaderParser::class);
            $request = m::mock(ServerRequestInterface::class);
            $handler = m::mock(RequestHandlerInterface::class);
            $response = m::mock(ResponseInterface::class);

            // Set up expectations
            $request->shouldReceive('getAttribute')
                ->with(BasicAuth::BasicAuthedKey, false)
                ->andReturn(true);
            $handler->shouldReceive('handle')
                ->with($request)
                ->andReturn($response);

            // Create an instance of BasicAuth with enabled set to true
            $basicAuth = new BasicAuth($authenticator, $parser, 'Web', true);

            // Call the process method
            $result = $basicAuth->process($request, $handler);

            // Assert that the result is the same as the response from the handler
            expect($result)->toBe($response);
        });

        it('should throw BasicAuthorizedException when authentication fails', function () {
            // Create mocks for dependencies
            $authenticator = m::mock(BasicAuthenticator::class);
            $parser = m::mock(BasicAuthHeaderParser::class);
            $request = m::mock(ServerRequestInterface::class);
            $handler = m::mock(RequestHandlerInterface::class);

            // Set up expectations
            $request->shouldReceive('getAttribute')
                ->with(BasicAuth::BasicAuthedKey, false)
                ->andReturn(false);
            $parser->shouldReceive('parseFromRequest')
                ->with($request)
                ->andReturn((object) ['username' => 'test', 'password' => 'wrongpassword']);
            $authenticator->shouldReceive('check')
                ->with('test', 'wrongpassword')
                ->andReturn(false);

            // Create an instance of BasicAuth with enabled set to true
            $basicAuth = new BasicAuth($authenticator, $parser, 'Web', true);

            // Call the process method and expect BasicAuthorizedException to be thrown
            $this->expectException(BasicAuthorizedException::class);
            $basicAuth->process($request, $handler);
        });
    }
)->group('BasicAuth');
