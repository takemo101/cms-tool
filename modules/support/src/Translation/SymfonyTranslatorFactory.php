<?php

namespace CmsTool\Support\Translation;

use DI\Attribute\Inject;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator as SymfonyTranslator;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Support\ApplicationSummary;

class SymfonyTranslatorFactory
{
    public const FallbackLocales = ['en'];

    /**
     * @var array<string,string> locale => locale extension
     */
    public const DefaultLocales = [
        'en' => 'en',
    ];

    /**
     * @var string
     */
    public const ValidationDomain = 'validations';

    /**
     * @var SymfonyTranslationLoaderType[]
     */
    private array $loaders = [];

    /**
     * @var array<string,string> locale => locale extension
     */
    private array $locales = [];

    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param ApplicationSummary $summary
     * @param PathHelper $pathHelper
     * @param string $defaultLocale
     * @param string[] $loaders
     * @param array<string,string> $locations domain => location
     * @param array<string,string> $locales locale => locale extension
     * @param string $cache
     */
    public function __construct(
        private LocalFilesystem $filesystem,
        private ApplicationSummary $summary,
        private PathHelper $pathHelper,
        #[Inject('config.app.locale')]
        private string $defaultLocale = 'en',
        #[Inject('config.support.translation.symfony.loaders')]
        array $loaders = [],
        #[Inject('config.support.translation.symfony.locations')]
        private array $locations = [],
        #[Inject('config.support.translation.symfony.locales')]
        array $locales = [],
    ) {
        $this->locales = [
            ...self::DefaultLocales,
            ...$locales,
        ];

        $this->loaders = array_map(
            fn (string $loader) => SymfonyTranslationLoaderType::tryFrom($loader),
            array_unique($loaders),
        );
    }

    /**
     * Create a new symfony translator instance.
     *
     * @return SymfonyTranslator
     */
    public function create(): SymfonyTranslator
    {
        $translator = new SymfonyTranslator(
            locale: $this->defaultLocale,
            debug: $this->summary->isDebugMode(),
        );

        $translator->setFallbackLocales(self::FallbackLocales);

        $this->addLoaders($translator);

        return $translator;
    }

    /**
     * Add loaders.
     * Add a loader to correspond to the specified type in $this->loaders
     *
     * @param SymfonyTranslator $translator
     * @return void
     */
    private function addLoaders(SymfonyTranslator $translator): void
    {
        foreach ($this->loaders as $loader) {
            match ($loader) {
                SymfonyTranslationLoaderType::PHP => $this->addPhpLoader($translator),
                SymfonyTranslationLoaderType::JSON => $this->addJsonLoader($translator),
            };
        }
    }

    /**
     * Add php loader.
     *
     * @param SymfonyTranslator $translator
     * @return void
     */
    private function addPhpLoader(SymfonyTranslator $translator): void
    {
        $format = 'php';

        $translator->addLoader($format, new PhpFileLoader());

        $this->addResources($translator, $format, $format);
    }

    /**
     * Add json loader.
     *
     * @param SymfonyTranslator $translator
     * @return void
     */
    private function addJsonLoader(SymfonyTranslator $translator): void
    {
        $format = 'json';

        $translator->addLoader($format, new JsonFileLoader());

        $this->addResources($translator, $format, $format);
    }

    /**
     * Add resources.
     *
     * @param SymfonyTranslator $translator
     * @param string $format
     * @param string $fileExt
     * @return void
     */
    private function addResources(SymfonyTranslator $translator, string $format, string $fileExt): void
    {
        foreach ($this->locations as $domain => $location) {
            foreach ($this->locales as $locale => $localeExt) {

                $path = "{$location}.{$localeExt}.{$fileExt}";

                if (!$this->filesystem->exists($path)) {
                    continue;
                }

                $translator->addResource($format, "{$location}.{$localeExt}.{$fileExt}", $locale, $domain);
            }
        }
    }
}
