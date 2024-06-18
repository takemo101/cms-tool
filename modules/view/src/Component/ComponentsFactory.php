<?php

namespace CmsTool\View\Component;

use DI\Attribute\Inject;

/**
 * Register the component to the key and get a value from the component
 */
class ComponentsFactory
{
    /**
     * constructor
     *
     * @param ComponentRenderer $renderer
     * @param array<string,class-string<object&callable>> $components
     */
    public function __construct(
        private ComponentRenderer $renderer,
        #[Inject('config.view.components')]
        private array $components = [],
    ) {
        //
    }

    /**
     * Create a new Components instance
     *
     * @return Components
     */
    public function create(): Components
    {
        return new Components(
            renderer: $this->renderer,
            components: $this->components,
        );
    }
}
