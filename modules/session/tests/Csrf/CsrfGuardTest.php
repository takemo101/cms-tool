<?php

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Session\Csrf\CsrfToken;
use Psr\Http\Message\ResponseFactoryInterface;

it(
    'should create a CSRF token',
    function () {
        $responseFactory = Mockery::mock(ResponseFactoryInterface::class);
        $session = [
            CsrfGuard::TokenNameKey => 'token-name',
            CsrfGuard::TokenValueKey => 'token-value',
        ];
        $guard = new CsrfGuard($responseFactory, $session);

        $token = $guard->getToken();

        expect($token)->toBeInstanceOf(CsrfToken::class);
        expect($token->name)->toBe($guard->getTokenName());
        expect($token->value)->toBe($guard->getTokenValue());
        expect(CsrfGuard::TokenNameKey)->toBe($guard->getTokenNameKey());
        expect(CsrfGuard::TokenValueKey)->toBe($guard->getTokenValueKey());
    }
)->group('csrf-guard', 'csrf');
