<?php

use CmsTool\Session\NativePhpSessionFactory;
use Odan\Session\SessionInterface as Session;
use Odan\Session\SessionManagerInterface as SessionManager;

it(
    'should create a session',
    function () {
        $factory = new NativePhpSessionFactory();
        $session = $factory->create();

        expect($session)->toBeInstanceOf(Session::class);
        expect($session)->toBeInstanceOf(SessionManager::class);
    }
)->group('session-factory', 'session');
