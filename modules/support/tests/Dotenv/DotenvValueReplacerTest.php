<?php

use CmsTool\Support\Dotenv\DotenvValueReplacer;

describe(
    'DotenvValueReplacer',
    function () {

        it('replaces null value in content', function () {
            $content = 'DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=null';

            $replacer = new DotenvValueReplacer();
            $replacedContent = $replacer->replace($content, 'DB_PASSWORD', null);

            expect($replacedContent)->toBe('DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=null');
        });

        it('replaces integer value in content', function () {
            $content = 'APP_PORT=8000';

            $replacer = new DotenvValueReplacer();
            $replacedContent = $replacer->replace($content, 'APP_PORT', 8080);

            expect($replacedContent)->toBe('APP_PORT=8080');
        });

        it('replaces float value in content', function () {
            $content = 'PI=3.14';

            $replacer = new DotenvValueReplacer();
            $replacedContent = $replacer->replace($content, 'PI', 3.14159);

            expect($replacedContent)->toBe('PI=3.14159');
        });

        it('replaces boolean value in content', function () {
            $content = 'DEBUG=false';

            $replacer = new DotenvValueReplacer();
            $replacedContent = $replacer->replace($content, 'DEBUG', true);

            expect($replacedContent)->toBe('DEBUG=true');
        });

        it('replaces string value in content', function () {
            $content = 'APP_NAME=MyApp';

            $replacer = new DotenvValueReplacer();
            $replacedContent = $replacer->replace($content, 'APP_NAME', 'NewApp');

            expect($replacedContent)->toBe('APP_NAME="NewApp"');
        });
    }
)->group('DotenvValueReplacer', 'dotenv');
