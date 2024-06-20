<?php

namespace Takemo101\CmsTool\Support\Theme;

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Hook\ThemeHookPresetResolver;
use CmsTool\Theme\ThemePathHelper;
use CmsTool\View\Accessor\DataAccessors;
use CmsTool\View\Contract\TemplateFinder;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\ApplicationContainer;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeRepository;
use Takemo101\CmsTool\HookTags;

class ActiveThemeFunctionLoader
{
    /** @var string */
    public const FunctionPath = 'function.php';

    /**
     * constructor
     *
     * @param ApplicationContainer $container
     * @param ActiveThemeRepository $repository
     * @param ThemeHookPresetResolver $resolver
     * @param Hook $hook
     * @param ThemePathHelper $path
     * @param LocalFilesystem $filesystem
     */
    public function __construct(
        private ApplicationContainer $container,
        private ActiveThemeRepository $repository,
        private ThemeHookPresetResolver $resolver,
        private Hook $hook,
        private ThemePathHelper $path,
        private LocalFilesystem $filesystem,
    ) {
        //
    }

    /**
     * Register theme route
     *
     * @return void
     */
    public function load(): void
    {
        $activeTheme = $this->repository->find();

        if (!$activeTheme) {
            return;
        }

        $this->container->set(ActiveTheme::class, $activeTheme);

        $this->beforeHook($activeTheme);

        // If there is a preset setting, register the preset route
        if ($name = $activeTheme->meta->preset) {
            $hook = $this->resolver->resolve($name);

            $hook?->hook($activeTheme, $this->hook);
        }

        $functionPath = $this->path->getThemePath($activeTheme, self::FunctionPath);

        if ($this->filesystem->exists($functionPath)) {
            $this->filesystem->require($functionPath);
        }

        $this->hook->doTyped($activeTheme);
        $this->hook->do(HookTags::Theme_LoadedActiveTheme, $activeTheme);
    }

    /**
     * Executes the "before" hook for the active theme.
     *
     * @param ActiveTheme $activeTheme The active theme object.
     * @return void
     */
    public function beforeHook(ActiveTheme $activeTheme): void
    {
        $this->hook
            ->onTyped(
                function (
                    TemplateFinder $finder,
                    ContainerInterface $container
                ) use ($activeTheme) {
                    /** @var ThemePathHelper */
                    $pathHelper = $container->get(ThemePathHelper::class);

                    $finder->addLocation(
                        $pathHelper->getTemplatePath($activeTheme),
                    );

                    return $finder;
                }
            )
            ->onTyped(
                fn (DataAccessors $accessors) => $accessors->add(
                    'theme',
                    fn () => $activeTheme,
                ),
            );
    }
}
