<?php

use CmsTool\View\View;
use CmsTool\View\ViewCreator;
use Takemo101\Chubby\Contract\Arrayable;
use Takemo101\Chubby\Support\ServiceLocator;

if (!function_exists('view')) {
    /**
     * Create a new view instance from the given arguments.
     *
     * @param string $name
     * @param array<string,mixed>|Arrayable<string,mixed> $data
     * @return View
     */
    function view(
        string $name,
        array|Arrayable $data = [],
    ): View {
        /** @var ViewCreator */
        $creator = ServiceLocator::container()->get(ViewCreator::class);

        return $creator->create($name, $data);
    }
}
