<?php

use CmsTool\Session\Contract\SessionFactory;
use CmsTool\Session\Flash\FlashSessionsFactory;
use CmsTool\Session\Middleware\SessionStart;
use Odan\Session\MemorySession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

beforeEach(function () {
    $this->sessionFactory = Mockery::mock(SessionFactory::class);
    $this->request = Mockery::mock(ServerRequestInterface::class);
    $this->handler = Mockery::mock(RequestHandlerInterface::class);
    $this->response = Mockery::mock(ResponseInterface::class);
});


describe(
    'SessionStart',
    function () {

        it(
            'should start the session if it is not already started',
            function () {
                $session = Mockery::mock(SessionManagerInterface::class, SessionInterface::class);
                $session->shouldReceive('isStarted')->andReturn(false);
                $session->shouldReceive('start');
                $session->shouldReceive('save');

                $this->sessionFactory->shouldReceive('create')->andReturn($session);
                $flashSessionsFactory = new FlashSessionsFactory();

                $this->handler->shouldReceive('handle')->andReturn($this->response);

                $this->request->shouldReceive('withAttribute')->andReturnSelf();

                $this->handler->shouldReceive('handle')->andReturn($this->response);

                $middleware = new SessionStart(
                    $this->sessionFactory,
                    $flashSessionsFactory
                );

                $actual = $middleware->process($this->request, $this->handler);

                expect($actual)->toBe($this->response);
            }
        );

        it(
            'should not start the session if it is already started',
            function () {
                $session = new MemorySession();
                $session->start();

                $this->sessionFactory->shouldReceive('create')->andReturn($session);
                $flashSessionsFactory = new FlashSessionsFactory();

                $this->handler->shouldReceive('handle')->andReturn($this->response);

                $this->request->shouldReceive('withAttribute')->andReturnSelf();

                $middleware = new SessionStart(
                    $this->sessionFactory,
                    $flashSessionsFactory
                );

                $actual = $middleware->process($this->request, $this->handler);

                expect($actual)->toBe($this->response);
            }
        );
    }
)->group('session-start-middleware', 'middleware');
