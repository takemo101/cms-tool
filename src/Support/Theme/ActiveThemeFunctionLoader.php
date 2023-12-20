<?php

namespace Takemo101\CmsTool\Support\Theme;

use CmsTool\Theme\ActiveThemeFactory;
use CmsTool\Theme\ThemePathHelper;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\CmsTool\Domain\Install\InstallRepository;

class ActiveThemeFunctionLoader
{
    /** @var string */
    public const FunctionPath = 'function.php';

    /**
     * constructor
     *
     * @param InstallRepository $repository
     * @param ActiveThemeFactory $factory
     * @param ThemePathHelper $path
     * @param LocalFilesystem $filesystem
     */
    public function __construct(
        private InstallRepository $repository,
        private ActiveThemeFactory $factory,
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
        if (!$this->repository->isInstalled()) {
            return;
        }

        $activeTheme = $this->factory->create();

        $functionPath = $this->path->getThemePath($activeTheme, self::FunctionPath);

        if ($this->filesystem->exists($functionPath)) {
            $this->filesystem->require($functionPath);
        }
    }
}
