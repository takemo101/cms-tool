<?php

namespace Takemo101\CmsTool\Domain\SiteMeta;

readonly class SiteMeta
{
    /**
     * constructor
     *
     * @param string $name
     * @param SiteSeo $seo
     */
    public function __construct(
        public string $name,
        public SiteSeo $seo,
    ) {
        assert(!empty($name), 'name is empty');
    }

    /**
     * Change name
     *
     * @param string $name
     * @return self
     */
    public function changeName(string $name): self
    {
        return new self(
            name: $name,
            seo: $this->seo,
        );
    }

    /**
     * Change seo
     *
     * @param SiteSeo $seo
     * @return self
     */
    public function changeSeo(SiteSeo $seo): self
    {
        return new self(
            name: $this->name,
            seo: $seo,
        );
    }

    /**
     * Clean image
     *
     * @param SiteSeoImageTarget $target
     * @return self
     */
    public function cleanSeoImage(SiteSeoImageTarget $target): self
    {
        return new self(
            name: $this->name,
            seo: $this->seo->cleanImage($target),
        );
    }

    /**
     * Create data for installation
     *
     * @param string $name
     * @return self
     */
    public static function install(
        string $name,
    ): self {
        return new self(
            name: $name,
            seo: new SiteSeo(
                title: $name,
            ),
        );
    }
}
