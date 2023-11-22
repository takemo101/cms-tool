<?php

use CmsTool\Session\Csrf\CsrfToken;
use Tests\TestCase;

describe(
    'CsrfToken',
    function () {

        it(
            'should create a new instance of CsrfToken',
            function () {
                $name = 'token_name';
                $value = 'token_value';

                $token = new CsrfToken($name, $value);

                expect($token)->toBeInstanceOf(CsrfToken::class);
                expect($token->name)->toBe($name);
                expect($token->value)->toBe($value);
            }
        );

        it(
            'should check if token is empty',
            function () {
                $emptyToken = new CsrfToken();
                $nonEmptyToken = new CsrfToken('token_name', 'token_value');

                expect($emptyToken->isEmpty())->toBeTrue();
                expect($nonEmptyToken->isEmpty())->toBeFalse();
            }
        );

        it(
            'should create a new instance of CsrfToken from a ServerRequestInterface object',
            function () {
                /** @var TestCase $this */
                $name = 'token_name';
                $value = 'token_value';

                $request = $this->createRequest('POST', '/');

                $request = $request
                    ->withAttribute(CsrfToken::NameKey, $name)
                    ->withAttribute(CsrfToken::ValueKey, $value);

                $token = CsrfToken::fromServerRequest($request);

                expect($token)->toBeInstanceOf(CsrfToken::class);
                expect($token->name)->toBe($name);
                expect($token->value)->toBe($value);
            }
        );
    }
)->group('csrf-token', 'csrf');
