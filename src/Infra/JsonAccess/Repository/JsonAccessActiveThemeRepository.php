<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Contract\ThemeFinder;
use CmsTool\Theme\Contract\ThemeLoader;
use CmsTool\Theme\Exception\NotFoundThemeException;
use CmsTool\Theme\Exception\ThemeLoadException;
use CmsTool\Theme\ThemeId;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessActiveThemeRepository implements ActiveThemeRepository
{
    /**
     * @var ActiveTheme|null
     */
    private ?ActiveTheme $activeTheme = null;

    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param ThemeFinder $finder
     * @param ThemeLoader $loader
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private ThemeFinder $finder,
        private ThemeLoader $loader,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     * å
     * @throws NotFoundThemeException|ThemeLoadException
     */
    public function find(): ?ActiveTheme
    {
        $object = $this->creator->create();

        /** @var string|null */
        $id = $object->get(SettingJsonObjectKeys::ActiveThemeIdKey);

        if (!$id) {
            return null;
        }

        $themeId = new ThemeId($id);

        // If the active theme is already loaded, return it
        if (
            ($theme = $this->activeTheme)
            && $theme->id->equals($themeId)
        ) {
            return $theme;
        }

        return $this->activeTheme = ActiveTheme::fromTheme(
            $this->loader->load(
                $this->finder->find($themeId),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function activate(ThemeId $id): void
    {
        $object = $this->creator->create();

        $object->set(SettingJsonObjectKeys::ActiveThemeIdKey, $id->value());

        $object->save();
    }
}
