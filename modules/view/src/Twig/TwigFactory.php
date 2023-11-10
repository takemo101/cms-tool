<?php

namespace CmsTool\View\Twig;

use Psr\Container\ContainerInterface;
use Stringable;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\EscaperExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\LoaderInterface;

final class TwigFactory
{
    /**
     * constructor
     *
     * @param ContainerInterface $container
     * @param LoaderInterface $loader
     * @param TwigOption $option
     * @param array<class-string<Stringable>,string[]> $safeClasses
     * @param class-string<ExtensionInterface>[] $extensions
     */
    public function __construct(
        private ContainerInterface $container,
        private LoaderInterface $loader,
        private TwigOption $option,
        private array $safeClasses,
        private array $extensions,
    ) {
        //
    }

    /**
     * Create a new Twig Environment.
     *
     * @return Environment
     */
    public function create(): Environment
    {
        $twig = new Environment(
            $this->loader,
            $this->option->toArray(),
        );

        if ($this->option->debug) {
            $twig->addExtension(new DebugExtension());
        }

        foreach ($this->safeClasses as $class => $strategies) {
            $twig->getExtension(
                EscaperExtension::class,
            )->addSafeClass($class, $strategies);
        }

        foreach ($this->extensions as $extension) {
            /** @var ExtensionInterface */
            $extensionInstance = $this->container->get($extension);

            $twig->addExtension($extensionInstance);
        }

        return $twig;
    }

    /**
     * Set the loader implementation.
     *
     * @param LoaderInterface $loader
     */
    public function setLoader(LoaderInterface $loader): self
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Set the option.
     *
     * @param TwigOption $option
     */
    public function setOption(TwigOption $option): self
    {
        $this->option = $option;

        return $this;
    }

    /**
     * @param class-string<Stringable> $class
     * @param string[] $strategies
     */
    public function addSafeClass(string $class, array $strategies): self
    {
        $this->safeClasses[$class] = $strategies;

        return $this;
    }

    /**
     * @param class-string<ExtensionInterface> $extension
     */
    public function addExtensionClass(string $extension): self
    {
        $this->extensions[] = $extension;

        return $this;
    }
}
