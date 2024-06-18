<?php

namespace CmsTool\Support;

use CmsTool\Support\Encrypt\Command\GenerateEncryptKeyCommand;
use CmsTool\Support\Encrypt\DefaultEncrypter;
use CmsTool\Support\Encrypt\EncryptCipher;
use CmsTool\Support\Encrypt\Encrypter;
use CmsTool\Support\JsonAccess\JsonArrayAccessor;
use CmsTool\Support\JsonAccess\JsonArrayLoader;
use CmsTool\Support\JsonAccess\JsonArraySaver;
use CmsTool\Support\JsonAccess\DefaultJsonAccessor;
use CmsTool\Support\Translation\Command\AddTranslationTextCommand;
use CmsTool\Support\Translation\DefaultTranslator;
use CmsTool\Support\Translation\SymfonyTranslationProxy;
use CmsTool\Support\Translation\TranslationAccessor;
use CmsTool\Support\Translation\TranslationJsonFileAccessor;
use CmsTool\Support\Translation\TranslationLoader;
use CmsTool\Support\Translation\TranslationSaver;
use CmsTool\Support\Translation\Translator;
use EventSauce\ObjectHydrator\ObjectMapper;
use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Hook\Hook;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Console\CommandCollection;
use RuntimeException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Takemo101\Chubby\Bootstrap\Support\ConfigBasedDefinitionReplacer;

use function DI\get;

class SupportProvider implements Provider
{
    /**
     * @var string Provider name.
     */
    public const ProviderName = 'support';

    /**
     * Execute Bootstrap providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function register(Definitions $definitions): void
    {
        $this->registerJsonAccess($definitions);
        $this->registerEncrypt($definitions);
        $this->registerValidation($definitions);
        $this->registerTranslation($definitions);

        $definitions->add([
            //
        ]);
    }

    /**
     * Execute Bootstrap booting process.
     *
     * @param ApplicationContainer $container
     * @return void
     */
    public function boot(ApplicationContainer $container): void
    {
        /** @var PathHelper */
        $helper = $container->get(PathHelper::class);

        /** @var ConfigRepository */
        $config = $container->get(ConfigRepository::class);

        $config->merge(
            'support',
            ConfigPhpRepository::getConfigByPath(
                $helper->join(dirname(__DIR__, 1), 'config', 'support.php')
            ),
            false,
        );

        require $helper->join(__DIR__, 'helper.php');

        /** @var Hook */
        $hook = $container->get(Hook::class);

        $hook->onTyped(
            fn (CommandCollection $commands) => $commands->add(
                GenerateEncryptKeyCommand::class,
                AddTranslationTextCommand::class,
            ),
        );
    }

    /**
     * Execute JsonAccess providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function registerJsonAccess(Definitions $definitions): void
    {
        $definitions->add([
            JsonArrayAccessor::class => new ConfigBasedDefinitionReplacer(
                DefaultJsonAccessor::class,
                'support.json.accessor',
            ),
            JsonArrayLoader::class => get(JsonArrayAccessor::class),
            JsonArraySaver::class => get(JsonArrayAccessor::class),
        ]);
    }

    /**
     * Execute Encrypt providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function registerEncrypt(Definitions $definitions): void
    {
        $definitions->add([
            EncryptCipher::class => function (
                ConfigRepository $config,
            ) {
                /** @var string|EncryptCipher */
                $cipher = $config->get('support.encrypt.cipher', EncryptCipher::AES_128_CBC);

                return EncryptCipher::fromAmbiguousValue($cipher);
            },
            DefaultEncrypter::class => function (
                EncryptCipher $cipher,
                ConfigRepository $config,
            ) {
                /** @var string */
                $base64key = $config->get('support.encrypt.key') ?? throw new RuntimeException('Encrypt key is not set.');

                $key = base64_decode($base64key);

                return new DefaultEncrypter(
                    $key,
                    $cipher,
                );
            },
            Encrypter::class => new ConfigBasedDefinitionReplacer(
                DefaultEncrypter::class,
                'support.encrypt.encrypter',
            ),
        ]);
    }

    /**
     * Execute Validation providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function registerValidation(Definitions $definitions): void
    {
        $definitions->add([
            ValidatorInterface::class => function (
                TranslatorInterface $translator,
            ) {
                return Validation::createValidatorBuilder()
                    ->enableAttributeMapping()
                    ->setTranslator($translator)
                    ->setTranslationDomain(SymfonyTranslationProxy::ValidationDomain)
                    ->getValidator();
            },
            ObjectMapper::class => fn () => new ObjectMapperUsingReflection(),
        ]);
    }

    public function registerTranslation(Definitions $definitions): void
    {
        $definitions->add([
            TranslatorInterface::class => get(SymfonyTranslationProxy::class),
            TranslationAccessor::class => new ConfigBasedDefinitionReplacer(
                TranslationJsonFileAccessor::class,
                'support.translation.accessor',
                true,
            ),
            TranslationLoader::class => get(TranslationAccessor::class),
            TranslationSaver::class => get(TranslationAccessor::class),
            Translator::class => new ConfigBasedDefinitionReplacer(
                DefaultTranslator::class,
                'support.translation.translator',
                true,
            ),
        ]);
    }
}
