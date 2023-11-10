<?php

namespace CmsTool\View\Contract;

interface TemplateFinder
{
    /**
     * Get the path to the template file from the name.
     *
     * @param string $name
     * @return string
     */
    public function find(string $name): string;

    /**
     * Add a location to the finder.
     *
     * @param string $location
     * @return void
     */
    public function addLocation(string $location): void;

    /**
     * Add a namespace location to the finder.
     *
     * @param string $namespace
     * @param string $location
     * @return void
     */
    public function addNamespace(string $namespace, string $location): void;
}
