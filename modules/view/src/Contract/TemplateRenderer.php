<?php

namespace CmsTool\View\Contract;

interface TemplateRenderer
{
    /**
     * Render the given template path.
     *
     * @param string $path
     * @param array<string,mixed> $data
     * @return string
     */
    public function renderTemplate(string $path, array $data = []): string;

    /**
     * Render the given template path with the given fragment.
     *
     * @param string $path
     * @param string[] $fragments
     * @param array<string,mixed> $data
     * @return string
     */
    public function renderFragments(string $path, array $fragments, array $data = []): string;

    /**
     * Render the given template string.
     *
     * @param string $template
     * @param array<string,mixed> $data
     * @return string
     */
    public function renderString(string $template, array $data = []): string;
}
