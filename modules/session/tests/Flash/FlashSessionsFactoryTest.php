<?php

use CmsTool\Session\Flash\FlashSession;
use CmsTool\Session\Flash\FlashSessions;
use CmsTool\Session\Flash\FlashSessionsFactory;
use Odan\Session\SessionInterface;

beforeEach(function () {
    $this->session = Mockery::mock(SessionInterface::class);
});

describe(
    'FlashSessionsFactory',
    function () {

        it(
            'should create a FlashSessions instance',
            function () {
                $flashSession = Mockery::mock(FlashSession::class)
                    ->shouldReceive('fromSession')
                    ->with($this->session)
                    ->andReturnSelf();

                $factory = new FlashSessionsFactory(get_class($flashSession->getMock()));

                $flashSessions = $factory->create($this->session);

                expect($flashSessions)->toBeInstanceOf(FlashSessions::class);
            }
        );

        it(
            'should create flash sessions of registered classes',
            function () {
                $class = Mockery::mock(FlashSession::class);

                $class->shouldReceive('fromSession')
                    ->with($this->session)
                    ->andReturnSelf();

                $factory = new FlashSessionsFactory(
                    get_class($class),
                );

                $flashSessions = $factory->create($this->session);

                expect($flashSessions->get(get_class($class)))->toBe($class);
            }
        );

        it(
            'should throw an exception when registering a non-subclass',
            function () {
                $this->expectException(InvalidArgumentException::class);

                new FlashSessionsFactory(InvalidFlashSession::class);
            }
        );
    }
)->group('flash-sessions-factory', 'flash');

class InvalidFlashSession
{
    // Invalid flash session class
}
