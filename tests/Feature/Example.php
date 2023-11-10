<?php

use Fig\Http\Message\StatusCodeInterface;
use Tests\TestCase;

it('root request', function () {
    /** @var TestCase $this */

    $response = $this->get('/');

    expect($response->getStatusCode())->toBe(StatusCodeInterface::STATUS_OK);
});

it('asset request', function (string $path, string $expectedContentType) {
    /** @var TestCase $this */

    $response = $this->get("/assets/{$path}");

    expect($response->getStatusCode())->toBe(StatusCodeInterface::STATUS_OK);
    expect($response->getHeaderLine('Content-Type'))->toBe($expectedContentType);
})->with([
    ['example.jpeg', 'image/jpeg'],
]);
