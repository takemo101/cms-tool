<?php

namespace Takemo101\CmsTool\Support\Accessor;

class ThemeCustomizationPreview
{
    /**
     * constructor
     *
     * @param array<string,array<string,mixed>>|null $preview
     */
    public function __construct(
        private ?array $preview = null,
    ) {
        //
    }

    /**
     * Set the preview data
     *
     * @param array<string,array<string,mixed>> $preview
     * @return void
     */
    public function set(array $preview): void
    {
        $this->preview = $preview;
    }

    /**
     * Get the preview data
     *
     * @return array<string,array<string,mixed>>|false
     */
    public function get(): array|false
    {
        return $this->preview ?? false;
    }
}
