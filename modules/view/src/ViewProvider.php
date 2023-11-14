<?php

namespace CmsTool\View;

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\Contract\TemplateRenderer;
use CmsTool\View\Twig\Command\TwigCleanCommand;
use CmsTool\View\Twig\Extension\ContextExtension;
use CmsTool\View\Twig\TwigFactory;
use CmsTool\View\Twig\TwigLoader;
use CmsTool\View\Twig\TwigOption;
use CmsTool\View\Twig\TwigTemplateRenderer;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\Chubby\Http\Context;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\LoaderInterface;
use Stringable;
use Takemo101\Chubby\Config\ConfigPhpRepository;

use function DI\get;

class ViewProvider implements Provider
{
    /**
     * @var string Provider name.
     */
    public const ProviderName = 'view';

    /**
     * Execute Bootstrap providing process.
     *
     * @param Definitions $definitions
     * @return void
     */
    public function register(Definitions $definitions): void
    {
        $definitions->add([
            TemplateFinder::class => function (
                ContainerInterface $container,
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<TemplateFinder> */
                $class = $config->get(
                    'view.finder',
                    DefaultTemplateFinder::class,
                );

                /** @var TemplateFinder */
                $finder = $container->get($class);

                /** @var TemplateFinder */
                $finder = $hook->filter(
                    TemplateFinder::class,
                    $finder,
                );

                return $finder;
            },
            TemplateRenderer::class => function (
                ContainerInterface $container,
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<TemplateRenderer> */
                $class = $config->get(
                    'view.renderer',
                    TwigTemplateRenderer::class,
                );

                /** @var TemplateRenderer */
                $renderer = $container->get($class);

                /** @var TemplateRenderer */
                $renderer = $hook->filter(
                    TemplateRenderer::class,
                    $renderer,
                );

                return $renderer;
            },
            TwigFactory::class => function (
                ContainerInterface $container,
                LoaderInterface $loader,
                TwigOption $option,
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var array<class-string<Stringable>,string[]> */
                $safeClasses = $config->get('view.twig.safe_classes', []);

                /** @var class-string<ExtensionInterface>[] */
                $extensions = $config->get('view.twig.extensions', []);

                $factory = new TwigFactory(
                    container: $container,
                    loader: $loader,
                    option: $option,
                    safeClasses: $safeClasses,
                    extensions: $extensions,
                );

                $hook->doByObject($factory);

                return $factory;
            },
            Environment::class => function (
                TwigFactory $factory,
                Hook $hook,
            ) {
                $twig = $factory->create();

                $hook->doByObject($twig);

                return $twig;
            },
            LoaderInterface::class => get(TwigLoader::class),
            ViewCreator::class => function (
                TemplateFinder $finder,
                TemplateRenderer $renderer,
                Hook $hook,
            ) {
                $creator = new ViewCreator(
                    finder: $finder,
                    renderer: $renderer,
                );

                $hook->doByObject($creator);

                return $creator;
            },
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
            'view',
            ConfigPhpRepository::getConfigByPath(
                $helper->join(
                    dirname(__DIR__, 1),
                    'config',
                    'view.php',
                ),
            ),
        );

        /** @var Hook */
        $hook = $container->get(Hook::class);

        $hook
            ->onByType(
                function (Context $context, ContainerInterface $container) {
                    /** @var Environment */
                    $twig = $container->get(Environment::class);

                    $twig->getExtension(ContextExtension::class)
                        ->setContext($context);
                }
            )->onByType(
                fn (CommandCollection $commands) => $commands->add(
                    TwigCleanCommand::class,
                ),
            );

        require $helper->join(__DIR__, 'helper.php');
    }
}
