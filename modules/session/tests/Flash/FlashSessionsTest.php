<?php

use CmsTool\Session\Flash\FlashSession;
use CmsTool\Session\Flash\FlashSessions;

describe(
    'FlashSessions',
    function () {

        it(
            'should add flash sessions',
            function () {
                $flashSession = Mockery::mock(FlashSession::class);

                $flashSessions = new FlashSessions($flashSession);

                $actual = $flashSessions->get(get_class($flashSession));

                expect($actual)->toBe($flashSession);
            }
        );

        it(
            'should throw an exception when getting a non-existent flash session',
            function () {
                $flashSessions = new FlashSessions();

                expect(
                    fn () => $flashSessions->get('NonExistentFlashSession')
                )->toThrow(InvalidArgumentException::class);
            }
        );
    }
)->group('flash-sessions', 'flash');
