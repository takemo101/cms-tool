<?php

use CmsTool\View\HtmlObject;
use Tests\TestCase;

describe(
    'HtmlObject',
    function () {
        test(
            'Obtains a string stored in an object',
            function (string $expected) {
                /** @var TestCase $this */

                $html = new HtmlObject($expected);

                expect((string) $html)->toBe($expected);
            },
        )->with([
            '<p>test<p>',
            'test',
        ]);
    }
)->group('html-object', 'view');
