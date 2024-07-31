<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

/**
 * Data class representing the hierarchical structure of header titles in the content.
 *
 * @property-read string|null $title
 * @property-read string|null $id
 * @immutable
 */
readonly class HeaderLayer
{
    /**
     * constructor
     *
     * @param HeaderTitleLevel $level
     * @param HeaderLayers $layers
     * @param LayeredHeaderTitle|null $title
     */
    public function __construct(
        public HeaderTitleLevel $level,
        public HeaderLayers $layers = new HeaderLayers(),
        public ?LayeredHeaderTitle $title = null,
    ) {
        //
    }

    /**
     * Select only the layers with the specified range of levels and create a new instance
     *
     * @param integer $start
     * @param integer $end
     * @return self
     */
    public function select(
        int $start = 1,
        int $end = 6,
    ): self {
        $layers = $this->layers->select($start, $end);

        return new self(
            level: $this->level,
            layers: $layers,
            title: $this->title,
        );
    }

    /**
     * Add a layer
     *
     * @param HeaderLayer $layer
     * @return self
     */
    public function add(HeaderLayer $layer): self
    {
        // If the level of the added layer is not the next level, create a new layer.
        return new self(
            level: $this->level,
            layers: !$this->level->isNext($layer->level) && $this->layers->isEmpty()
                ? $this->layers->add(
                    (
                        new HeaderLayer(
                            level: $this->level->next(),
                        )
                    )->add($layer)
                )
                : $this->layers->add($layer),
            title: $this->title,
        );
    }

    /**
     * Check if there is a title
     *
     * @return boolean
     */
    public function hasHeaderTitle(): bool
    {
        return $this->title !== null;
    }

    /**
     * Get the title properties
     *
     * @return LayeredHeaderTitle
     */
    public function __get(string $name)
    {
        return match ($name) {
            'title' => $this->title?->title,
            'id' => $this->title?->id,
            default => null,
        };
    }
}
