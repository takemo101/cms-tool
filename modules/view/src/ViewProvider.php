<?php

namespace CmsTool\View;

use CmsTool\View\Accessor\DataAccessAdapter;
use CmsTool\View\Accessor\DataAccessInvoker;
use CmsTool\View\Accessor\DataAccessors;
use CmsTool\View\Accessor\DataAccessorsFactory;
use CmsTool\View\Accessor\DefaultDataAccessInvoker;
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
                $finder = $hook->do(
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
                $renderer = $hook->do(
                    TemplateRenderer::class,
                    $renderer,
                );

                return $renderer;
            },
            DataAccessInvoker::class =>
            function (
                ContainerInterface $container,
                ConfigRepository $config,
                Hook $hook,
            ) {
                /** @var class-string<DataAccessInvoker> */
                $class = $config->get(
                    'view.invoker',
                    DefaultDataAccessInvoker::class,
                );

                /** @var DataAccessInvoker */
                $invoker = $container->get($class);

                /** @var TemplateRenderer */
                $invoker = $hook->do(
                    DataAccessInvoker::class,
                    $invoker,
                );

                return $invoker;
            },
            DataAccessors::class => function (
                DataAccessorsFactory $factory,
                Hook $hook,
            ) {
                $accessors = $factory->create();

                $hook->doByType($accessors);

                return $accessors;
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

                $hook->doByType($factory);

                return $factory;
            },
            Environment::class => function (
                TwigFactory $factory,
                Hook $hook,
            ) {
                $twig = $factory->create();

                $hook->doByType($twig);

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

                $hook->doByType($creator);

                return $creator;
            },
            AttrTransformers::class => function (
                Hook $hook,
            ) {
                $transformers = new AttrTransformers(
                    new ArrayAttrTransformer(),
                    new BooleanAttrTransformer(),
                );

                $hook->doByType($transformers);

                return $transformers;
            },
            FormAppendFilters::class => function (
                Hook $hook,
            ) {
                $filters = new FormAppendFilters(
                    new AppendMethodOverrideInputFilter(),
                );

                $hook->doByType($filters);

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
            ->onByType(
                function (ServerRequestInterface $request, ContainerInterface $container) {
                    /** @var Environment */
                    $twig = $container->get(Environment::class);

                    $twig->getExtension(RequestExtension::class)
                        ->setServerRequest($request);

                    return $request;
                }
            )->onByType(
                fn (CommandCollection $commands) => $commands->add(
                    TwigCleanCommand::class,
                ),
            );

        require $helper->join(__DIR__, 'helper.php');
    }
}
