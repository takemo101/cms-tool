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
use Takemo101\Chubby\Bootstrap\DefinitionHelper;
use Takemo101\Chubby\Config\ConfigPhpRepository;
use Takemo101\Chubby\Console\CommandCollection;
use RuntimeException;

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

        $this->bootEncrypt($container);
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
            JsonArrayAccessor::class => DefinitionHelper::createReplaceableDefinition(
                JsonArrayAccessor::class,
                'support.json.accessor',
                DefaultJsonAccessor::class,
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
            Encrypter::class => DefinitionHelper::createReplaceableDefinition(
                Encrypter::class,
                'support.encrypt.encrypter',
                DefaultEncrypter::class,
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
            ValidatorInterface::class => function () {
                return Validation::createValidatorBuilder()
                    ->enableAnnotationMapping()
                    ->getValidator();
            },
            ObjectMapper::class => fn () => new ObjectMapperUsingReflection(),
        ]);
    }

    /**
     * Execute Encrypt booting process.
     *
     * @param ApplicationContainer $container
     * @return void
     */
    public function bootEncrypt(ApplicationContainer $container): void
    {
        /** @var Hook */
        $hook = $container->get(Hook::class);

        $hook->onTyped(
            fn (CommandCollection $commands) => $commands->add(
                GenerateEncryptKeyCommand::class,
            ),
        );
    }
}
