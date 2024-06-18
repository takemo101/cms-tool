<?php

namespace CmsTool\View;

use CmsTool\View\Accessor\DataAccessAdapter;
use CmsTool\View\Accessor\DataAccessors;
use CmsTool\View\Accessor\DataAccessorsFactory;
use CmsTool\View\Component\Components;
use CmsTool\View\Component\ComponentsFactory;
use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\Contract\TemplateRenderer;
use CmsTool\View\Html\Filter\AppendMethodOverrideInputFilter;
use CmsTool\View\Html\Filter\FormAppendFilters;
use CmsTool\View\Html\Transformer\ArrayAttrTransformer;
use CmsTool\View\Html\Transformer\AttrTransformers;
use CmsTool\View\Html\Transformer\BooleanAttrTransformer;
use CmsTool\View\Twig\Command\TwigCleanCommand;
use CmsTool\View\Twig\Extension\RequestExtension;
use CmsTool\View\Twig\TwigFactory;
use CmsTool\View\Twig\TwigLoader;
use CmsTool\View\Twig\TwigOption;
use CmsTool\View\Twig\TwigTemplateRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Bootstrap\Definitions;
use Takemo101\Chubby\Bootstrap\Provider\Provider;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Console\CommandCollection;
use Takemo101\Chubby\Filesystem\PathHelper;
use Takemo101\Chubby\Hook\Hook;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\LoaderInterface;
use Stringable;
use Takemo101\Chubby\Bootstrap\Support\ConfigBasedDefinitionReplacer;
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
            TemplateFinder::class => new ConfigBasedDefinitionReplacer(
                DefaultTemplateFinder::class,
                'view.finder',
                true,
            ),
            TemplateRenderer::class => new ConfigBasedDefinitionReplacer(
                TwigTemplateRenderer::class,
                'view.renderer',
            ),
            DataAccessors::class => function (
                DataAccessorsFactory $factory,
                Hook $hook,
            ) {
                $accessors = $factory->create();

                $hook->doTyped($accessors);

                return $accessors;
            },
            Components::class => function (
                ComponentsFactory $factory,
                Hook $hook,
            ) {
                $components = $factory->create();

                $hook->doTyped($components);

                return $components;
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

                $hook->doTyped($factory);

                return $factory;
            },
            Environment::class => function (
                TwigFactory $factory,
                Hook $hook,
            ) {
                $twig = $factory->create();

                $hook->doTyped($twig);

                return $twig;
            },
            LoaderInterface::class => get(TwigLoader::class),
            ViewCreator::class => function (
                TemplateFinder $finder,
                TemplateRenderer $renderer,
                DataAccessAdapter $adapter,
                Hook $hook,
            ) {
                $creator = new ViewCreator(
                    finder: $finder,
                    renderer: $renderer,
                );

                $creator->share('share', $adapter);

                $hook->doTyped($creator);

                return $creator;
            },
            AttrTransformers::class => function (
                Hook $hook,
            ) {
                $transformers = new AttrTransformers(
                    new ArrayAttrTransformer(),
                    new BooleanAttrTransformer(),
                );

                $hook->doTyped($transformers);

                return $transformers;
            },
            FormAppendFilters::class => function (
                Hook $hook,
            ) {
                $filters = new FormAppendFilters(
                    new AppendMethodOverrideInputFilter(),
                );

                $hook->doTyped($filters);

                return $filters;
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
                $helper->join(dirname(__DIR__, 1), 'config', 'view.php')
            ),
            false,
        );

        /** @var Hook */
        $hook = $container->get(Hook::class);

        $hook
            ->onTyped(
                function (ServerRequestInterface $request, ContainerInterface $container) {
                    /** @var Environment */
                    $twig = $container->get(Environment::class);

                    $twig->getExtension(RequestExtension::class)
                        ->setServerRequest($request);

                    return $request;
                }
            )->onTyped(
                fn (CommandCollection $commands) => $commands->add(
                    TwigCleanCommand::class,
                ),
            );

        require $helper->join(__DIR__, 'helper.php');
    }
}
