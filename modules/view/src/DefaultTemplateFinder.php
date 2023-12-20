<?php

namespace CmsTool\View;

use CmsTool\View\Contract\TemplateFinder;
use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Filesystem\PathHelper;

class DefaultTemplateFinder implements TemplateFinder
{
    /**
     * @var array<string,string>
     */
    private array $views = [];

    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param PathHelper $helper
     * @param string[] $locations
     * @param array<string,string> $namespaces
     * @param string[] $extensions
     */
    public function __construct(
        protected readonly LocalFilesystem $filesystem,
        protected readonly PathHelper $helper = new PathHelper(),
        #[Inject('config.view.locations')]
        private array $locations = [],
        #[Inject('config.view.namespaces')]
        private array $namespaces = [],
        #[Inject('config.view.extensions')]
        private array $extensions = ['php', 'html'],
    ) {
        //
    }

    /**
     * Determine if a given template exists.
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        try {
            $this->find($name);
        } catch (NotFoundTemplateException) {
            return false;
        }

        return true;
    }

    /**
     * Get the path to the template file from the name.
     *
     * @param string $name
     * @return string
     * @throws NotFoundTemplateException
     */
    public function find(string $name): string
    {
        if ($path = $this->views[$name] ?? false) {
            return $path;
        }

        $view = ViewName::fromOriginal($name);

        if ($view->hasNamespace()) {
            return $this->views[$name] = $this->findInNamespaces($view);
        }

        return $this->views[$name] = $this->findInLocations($view);
    }

    /**
     * Get the path to the template file from the namespace.
     *
     * @param ViewName $view
     * @return string
     * @throws NotFoundTemplateException
     */
    private function findInNamespaces(ViewName $view): string
    {
        if ($namespacePath = $this->namespaces[$view->getNamespace()] ?? false) {
            foreach ($this->extensions as $extension) {
                $path = $this->helper->join(
                    $namespacePath,
                    $view->getPath() . '.' . $extension,
                );

                if ($this->filesystem->exists($path)) {
                    return $path;
                }
            }
        }

        throw new NotFoundTemplateException($view->getOriginal());
    }

    /**
     * Get the path to the template file from the locations.
     *
     * @param ViewName $view
     * @return string
     * @throws NotFoundTemplateException
     */
    private function findInLocations(ViewName $view): string
    {
        $namePath = $view->getPath();

        foreach ($this->locations as $location) {
            foreach ($this->extensions as $extension) {
                $path = $this->helper->join(
                    $location,
                    $namePath . '.' . $extension,
                );

                if ($this->filesystem->exists($path)) {
                    return $path;
                }
            }
        }

        throw new NotFoundTemplateException($view->getOriginal());
    }

    /**
     * Add a location to the finder.
     *
     * @param string $location
     * @return void
     */
    public function addLocation(string $location): void
    {
        $this->locations = array_unique([
            ...$this->locations,
            $this->filesystem->realpath($location) ?? $location,
        ]);
    }

    /**
     * Add a namespace location to the finder.
     *
     * @param string $namespace
     * @param string $location
     * @return void
     */
    public function addNamespace(string $namespace, string $location): void
    {
        $this->namespaces[$namespace] = $location;
    }

    /**
     * Add a valid view extension to the finder.
     *
     * @param string $extension
     * @return void
     */
    public function addExtension(string $extension): void
    {
        $this->locations = array_unique([
            ...$this->locations,
            $extension,
        ]);
    }

    /**
     * Get the view locations.
     *
     * @return string[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * Get the view namespaces.
     *
     * @return array<string,string>
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * Get the view extensions.
     *
     * @return string[]
     */
    public function getExtentions(): array
    {
        return $this->extensions;
    }
}
