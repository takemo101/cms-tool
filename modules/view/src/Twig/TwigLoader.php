<?php

namespace CmsTool\View\Twig;

use CmsTool\View\Contract\TemplateFinder;
use CmsTool\View\NotFoundTemplateException;
use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;

/**
 * Basic loader using absolute paths.
 */
class TwigLoader implements LoaderInterface
{
    /**
     * @var array<string,string>
     */
    private $cache = [];

    /**
     * constructor
     *
     * @param LocalFilesystem $filesystem
     * @param TemplateFinder $finder
     * @param string[] $extensions
     */
    public function __construct(
        private readonly LocalFilesystem $filesystem,
        private readonly TemplateFinder $finder,
        #[Inject('config.view.extensions')]
        private array $extensions = ['twig'],
    ) {
        //
    }

    /**
     * Return path to template without the need for the extension.
     *
     * @param string $name Template file name or path.
     *
     * @throws LoaderError
     * @return string Path to template
     */
    public function findTemplate(string $name)
    {
        if ($this->filesystem->exists($name)) {
            return $name;
        }

        $name = $this->normalizeTemplateName($name);

        if ($path = $this->cache[$name] ?? false) {
            return $path;
        }

        try {
            $path = $this->cache[$name] = $this->finder->find($name);
        } catch (NotFoundTemplateException $e) {
            throw new LoaderError($e->getMessage());
        }

        return $path;
    }

    /**
     * Normalize the Twig template name
     *
     * @param  string $name Template file name.
     * @return string The parsed name
     */
    private function normalizeTemplateName($name)
    {
        foreach ($this->extensions as $extension) {
            if (substr($name, - (strlen($extension) + 1)) === $extension) {
                return substr($name, 0, - (strlen($extension) + 1));
            }
        }

        return $name;
    }

    /**
     * Returns the source context for a given template logical name.
     *
     * @param String $name
     * @return Source
     */
    public function getSourceContext(String $name): Source
    {
        $path = $this->findTemplate($name);

        return new Source(
            $this->filesystem->read($path) ?? '',
            $name,
            $path,
        );
    }

    /**
     * @param string $name
     *
     * @throws LoaderError When $name is not found
     */
    public function getCacheKey(string $name): string
    {
        return $this->findTemplate($name);
    }

    /**
     * @param string $name
     * @param integer $time
     * @return boolean
     * @throws LoaderError When $name is not found
     */
    public function isFresh(string $name, int $time): bool
    {
        return $this->filesystem->time(
            $this->findTemplate($name),
        ) <= $time;
    }

    /**
     * @return bool
     */
    public function exists(string $name)
    {
        try {
            $this->findTemplate($name);
        } catch (LoaderError $e) {
            return false;
        }

        return true;
    }
}
