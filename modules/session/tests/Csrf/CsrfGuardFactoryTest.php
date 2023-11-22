<?php

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Session\Csrf\CsrfGuardFactory;
use CmsTool\Session\Csrf\HttpCsrfTokenMismatchException;
use Odan\Session\MemorySession;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\TestCase;

describe(
    'CsrfGuardFactory',
    function () {

        it(
            'should create a CsrfGuard instance',
            function () {
                $responseFactory = Mockery::mock(ResponseFactoryInterface::class);
                $session = Mockery::mock(SessionInterface::class);

                $session->shouldReceive('all')->andReturn([]);

                $factory = new CsrfGuardFactory($responseFactory);

                $guard = $factory->create($session);

                expect($guard)->toBeInstanceOf(CsrfGuard::class);
            }
        );

        it('should return a callable that throws an HttpCsrfTokenMismatchException', function () {
            /** @var TestCase $this */

            $responseFactory = Mockery::mock(ResponseFactoryInterface::class);
            $session = new MemorySession();

            $factory = new CsrfGuardFactory($responseFactory);

            $guard = $factory->create($session);

            $request = $this->createRequest('POST');

            $handler = Mockery::mock(RequestHandlerInterface::class);

            $handler->shouldReceive('handle')->andReturn($this->createResponse());

            expect(fn () => $guard->process(
                $request,
                $handler
            ))->toThrow(HttpCsrfTokenMismatchException::class);
        });
    }
)->group('csrf-guard-factory', 'csrf');
