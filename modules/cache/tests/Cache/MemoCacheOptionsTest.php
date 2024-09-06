<?php

use CmsTool\Cache\MemoCacheOptions;

it('can create options with caching enabled and a set lifetime', function () {
    $lifetime = 3600;
    $options = MemoCacheOptions::createWithLifetime($lifetime);

    expect($options->enabled)->toBeTrue();
    expect($options->lifetime)->toBe($lifetime);
})->group('MemoCacheOptions', 'cache');
