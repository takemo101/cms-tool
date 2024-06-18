<?php

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Session\Csrf\CsrfGuardFactory;
use CmsTool\Session\Middleware\Csrf;
use CmsTool\Session\Csrf\HttpCsrfTokenMismatchException;
use CmsTool\Session\SessionContext;
use Odan\Session\MemorySession;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\Session\TestCase;

describe(
    'CsrfMiddleware',
    function () {

        test(
            'Exceptions occur if the token does not match',
            function () {
                /** @var TestCase $this */

                $context = new SessionContext(new MemorySession());
                $request = $context->withRequest($this->createRequest('POST', '/'));

                $response = $this->createResponse();
                $handler = Mockery::mock(RequestHandlerInterface::class);

                $handler->shouldReceive('handle')->andReturn($response);

                /** @var Csrf */
                $csrf = $this->getContainer()->get(Csrf::class);

                // Exceptions occur because the tokens do not match
                expect(fn () => $csrf->process($request, $handler))
                    ->toThrow(HttpCsrfTokenMismatchException::class);
            }
        );

        test(
            'The response returns because the token matches',
            function () {
                /** @var TestCase $this */

                $session = new MemorySession();
                $context = new SessionContext($session);

                $response = $this->createResponse();
                $handler = Mockery::mock(RequestHandlerInterface::class);

                $handler->shouldReceive('handle')->andReturn($response);

                /** @var CsrfGuardFactory */
                $factory = $this->getContainer()->get(CsrfGuardFactory::class);

                $guard = $factory->create($session);

                $tokenPair = $guard->generateToken();

                $request = $context
                    ->withRequest($this->createRequest('POST', '/'))->withParsedBody($tokenPair);

                $actual = (new Csrf($factory))->process($request, $handler);

                expect($actual)->toBe($response);

                $excepted = $tokenPair;
                $actual = [
                    CsrfGuard::TokenNameKey => $guard->getTokenName(),
                    CsrfGuard::TokenValueKey => $guard->getTokenValue(),
                ];

                expect($actual)->toBe($excepted);
            }
        );
    }
)->group('csrf-middleware', 'middleware');
