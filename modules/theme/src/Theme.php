<?php

namespace CmsTool\Theme;

use CmsTool\Theme\Exception\ThemeSpecException;

class Theme
{
    /**
     * constructor
     *
     * @param ThemeId $id
     * @param string $directory
     * @param ThemeMeta $meta
     * @param boolean $active
     */
    public function __construct(
        public readonly ThemeId $id,
        public readonly string $directory,
        public readonly ThemeMeta $meta,
        private bool $active = false,
    ) {
        //
    }

    /**
     * Change the theme metadata
     *
     * @return self
     * @throws ThemeSpecException
     */
    public function changeMeta(ThemeMeta $meta): self
    {
        if (!$this->canBeEdited()) {
            throw ThemeSpecException::cannotBeEditedError($this);
        }

        return new self(
            id: $this->id,
            directory: $this->directory,
            meta: $meta,
            active: $this->active,
        );
    }

    /**
     * Return whether it's an active theme
     *
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Return whether it's a readonly theme
     *
     * @return bool
     */
    public function isReadonly(): bool
    {
        return $this->meta->readonly;
    }

    /**
     * Can the theme be deleted?
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return !(
            $this->isReadonly() || $this->isActive()
        );
    }

    /**
     * Can the theme be edited?
     *
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return !$this->isReadonly();
    }

    /**
    /**
     * Can the theme be customized?
     *
     * @return bool
     */
    public function canBeCustomized(): bool
    {
        return !$this->meta->schema->isInputSettingEmpty();
    }

    /**
     * Refines the theme's customization data with the default values of the schema settings.
     *
     * @param array<string,array<string,mixed>> $data The theme's customization data
     * @return array<string,array<string,mixed>>
     */
    public function refineCustomizationWithDefaults(array $data = []): array
    {
        return $this->meta->schema->refineCustomizationWithDefaults($data);
    }

    /**
     * Refines the theme's customization data with the not set values of the schema settings.
     *
     * @param array<string,array<string,mixed>> $data The theme's customization data
     * @return array<string,array<string,mixed>>
     */
    public function refineCustomizationWithNotSet(array $data = []): array
    {
        return $this->meta->schema->refineCustomizationWithNotSet($data);
    }

    /**
     * Delete the theme
     * If the theme is active or readonly, an exception will be thrown.
     *
     * @return string The path of the deleted theme
     */
    public function delete(): string
    {
        if (!$this->canBeDeleted()) {
            throw ThemeSpecException::cannotBeDeletedError($this);
        }

        return $this->directory;
    }

    /**
     * Create a copy of the theme
     *
     * @return self
     */
    public function copy(
        ThemeId $id,
        string $directory,
    ): self {
        return new self(
            id: $id,
            directory: $directory,
            meta: $this->meta->copy(),
            active: false,
        );
    }
}
