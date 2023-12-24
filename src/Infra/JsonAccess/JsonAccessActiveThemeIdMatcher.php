<?php

namespace Takemo101\CmsTool\Infra\JsonAccess;

use CmsTool\Theme\Contract\ActiveThemeIdMatcher;
use CmsTool\Theme\ThemeId;

class JsonAccessActiveThemeIdMatcher implements ActiveThemeIdMatcher
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     */
    public function __construct(
        private readonly SettingJsonAccessObjectCreator $creator,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function isMatch(ThemeId $id): bool
    {
        $object = $this->creator->create();

        /** @var string|null */
        $activeThemeId = $object->get(SettingJsonObjectKeys::ActiveThemeIdKey);

        if (!$activeThemeId) {
            return false;
        }

        return $activeThemeId === $id->value();
    }
}
