<?php

use CmsTool\Session\SessionAccessAdapter;
use Odan\Session\MemorySession;
use Odan\Session\SessionInterface as Session;

describe(
    'SessionAccessAdapter',
    function () {

        it(
            'should set and get values',
            function (string $key, $value) {
                $session = new MemorySession();

                $adapter = new SessionAccessAdapter($session);

                $adapter[$key] = $value;

                expect($adapter[$key])->toBe($value);
            }
        )->with([
            ['foo', 'bar'],
            ['baz', 'qux'],
        ]);

        it(
            'should check if offset exists',
            function (string $key) {
                $sessionMock = Mockery::mock(Session::class);

                $invalidKey = 'invalid-key';

                $sessionMock->shouldReceive('all')->andReturn([]);
                $sessionMock->shouldReceive('has')->with($key)->andReturn(true);
                $sessionMock->shouldReceive('has')->with($invalidKey)->andReturn(false);

                $adapter = new SessionAccessAdapter($sessionMock);

                expect(isset($adapter[$key]))->toBeTrue();
                expect(isset($adapter[$invalidKey]))->toBeFalse();
            }
        )->with([
            'foo',
            'baz',
        ]);

        it(
            'should delete values',
            function (string $key, $value) {
                $session = new MemorySession();

                $adapter = new SessionAccessAdapter($session);

                $adapter[$key] = $value;

                expect(isset($adapter[$key]))->toBeTrue();

                unset($adapter[$key]);

                expect(isset($adapter[$key]))->toBeFalse();
            }
        )->with([
            ['foo', 'bar'],
            ['baz', 'qux'],
        ]);

        it(
            'should iterate over values',
            function (array $data) {
                $session = new MemorySession();

                $adapter = new SessionAccessAdapter($session);

                foreach ($data as $key => $value) {
                    $adapter[$key] = $value;
                }

                $expected = $data;

                foreach ($adapter as $key => $value) {
                    expect($value)->toBe($expected[$key]);
                }
            }
        )->with([
            fn () => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            fn () => [
                'foo' => 'bar',
                'baz' => 'qux',
                'quux' => 'quuz',
            ],
        ]);

        it(
            'should count values',
            function (array $data) {
                $sessionMock = Mockery::mock(Session::class);
                $sessionMock->shouldReceive('all')->andReturn($data);

                $adapter = new SessionAccessAdapter($sessionMock);

                $expected = count($data);

                expect(count($adapter))->toBe($expected);
            }
        )->with([
            fn () => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            fn () => [
                'foo' => 'bar',
                'baz' => 'qux',
                'quux' => 'quuz',
            ],
        ]);
    }
)->group('session-access-adapter', 'session');
