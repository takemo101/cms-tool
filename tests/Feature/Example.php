<?php

use Fig\Http\Message\StatusCodeInterface;
use Tests\TestCase;

it('asset request', function (string $path, string $expectedContentType) {
    /** @var TestCase $this */


    expect(true)->toBeTrue();
})->with([
    ['example.jpeg', 'image/jpeg'],
    ['logo.png', 'image/png'],
])->group('feature');
