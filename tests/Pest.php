<?php

use Tests\Session\TestCase as SessionTestCase;
use Tests\Support\TestCase as SupportTestCase;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

// for session
uses(SessionTestCase::class)->in(
    '../modules/session/tests/Csrf',
    '../modules/session/tests/Middleware',
);

// for support
uses(SupportTestCase::class)->in(
    '../modules/support/tests/JsonAccess',
    '../modules/support/tests/Encrypt',
);
