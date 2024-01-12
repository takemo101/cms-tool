<?php

use CmsTool\Support\Translation\DefaultTranslator;
use CmsTool\Support\Translation\Exception\TranslationTypeErrorException;
use CmsTool\Support\Translation\TranslationLoader;
use CmsTool\Support\Translation\TranslationStringReplacer;
use Mockery as m;

describe(
    'DefaultTranslator',
    function () {

        it('should check if a translation key exists', function () {
            $loader = m::mock(TranslationLoader::class);
            $replacer = m::mock(TranslationStringReplacer::class);
            $translator = new DefaultTranslator($loader, $replacer);

            $key = 'app.welcome';
            $domain = 'app';
            $locale = 'en';

            $loader->shouldReceive('exists')
                ->once()
                ->with($domain)
                ->andReturn(true);

            $loader->shouldReceive('load')
                ->once()
                ->with($domain, $locale)
                ->andReturn(['welcome' => 'Welcome to the app']);

            $exists = $translator->exists($key, $locale);

            expect($exists)->toBeTrue();
        });

        it('should return the translated string', function () {
            $loader = m::mock(TranslationLoader::class);
            $replacer = m::mock(TranslationStringReplacer::class);
            $translator = new DefaultTranslator($loader, $replacer);

            $key = 'app.welcome';
            $domain = 'app';
            $locale = 'en';
            $translation = 'Welcome to the app';
            $replace = ['name' => 'John'];

            $loader->shouldReceive('load')
                ->once()
                ->with($domain, $locale)
                ->andReturn(['welcome' => $translation]);

            $replacer->shouldReceive('replace')
                ->once()
                ->with($translation, $replace)
                ->andReturn('Welcome to the app, John');

            $translated = $translator->translate($key, $replace, $locale);

            expect($translated)->toBe('Welcome to the app, John');
        });

        it('should throw an exception if the translation is not a string', function () {
            $loader = m::mock(TranslationLoader::class);
            $replacer = m::mock(TranslationStringReplacer::class);
            $translator = new DefaultTranslator($loader, $replacer);

            $key = 'app.welcome';
            $domain = 'app';
            $locale = 'en';
            $translation = ['welcome' => null];

            $loader->shouldReceive('load')
                ->once()
                ->with($domain, $locale)
                ->andReturn($translation);

            expect(fn () => $translator->translate($key, [], $locale))
                ->toThrow(TranslationTypeErrorException::class);
        });

        it('should get the default locale', function () {
            $loader = m::mock(TranslationLoader::class);
            $replacer = m::mock(TranslationStringReplacer::class);
            $translator = new DefaultTranslator($loader, $replacer);

            $defaultLocale = 'en';

            $translator->setLocale($defaultLocale);

            $locale = $translator->getLocale();

            expect($locale)->toBe($defaultLocale);
        });
    }
)->group('DefaultTranslator', 'translation');
