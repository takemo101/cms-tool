<?php

namespace Takemo101\CmsTool\Preset\Shared\HeaderTitle;

use Takemo101\CmsTool\Support\Shared\HasCamelCaseAccess;

/**
 * Data class representing the hierarchical structure of header titles in the content.
 *
 * @property-read string|null $title
 * @property-read string|null $id
 * @immutable
 */
readonly class HeaderLayer
{
    use HasCamelCaseAccess;

    /**
     * constructor
     *
     * @param HeaderTitleLevel $level
     * @param HeaderLayers $layers
     * @param LayeredHeaderTitle|null $header
     */
    public function __construct(
        public HeaderTitleLevel $level,
        public HeaderLayers $layers = new HeaderLayers(),
        public ?LayeredHeaderTitle $header = null,
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
            header: $this->header,
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
            header: $this->header,
        );
    }

    /**
     * Check if the layer is empty
     *
     * @return boolean
     */
    public function isBlank(): bool
    {
        return $this->header === null;
    }

    /**
     * Check if the layer is not empty
     *
     * @return boolean
     */
    public function isNotBlank(): bool
    {
        return !$this->isBlank();
    }

    /**
     * {@inheritdoc}
     */
    protected function __properties(): array
    {
        return [
            'title' => $this->header?->title,
            'id' => $this->header?->id,
        ];
    }
}
