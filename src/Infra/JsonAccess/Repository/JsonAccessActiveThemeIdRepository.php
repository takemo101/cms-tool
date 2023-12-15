<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use CmsTool\Theme\ActiveThemeId;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeIdRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessActiveThemeIdRepository implements ActiveThemeIdRepository
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function find(): ?ActiveThemeId
    {
        $object = $this->creator->create();

        if (!$object->has(SettingJsonObjectKeys::ActiveThemeIdKey)) {
            return null;
        }

        /** @var string */
        $id = $object->get(SettingJsonObjectKeys::ActiveThemeIdKey);

        return new ActiveThemeId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(ActiveThemeId $id): void
    {
        $object = $this->creator->create();

        $object->set(SettingJsonObjectKeys::ActiveThemeIdKey, $id->value());

        $object->save();
    }
}
