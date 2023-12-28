<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Install\Installer;
use Takemo101\CmsTool\Domain\Management\ManagementType;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessInstaller implements Installer
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
    public function install(): void
    {
        $object = $this->creator->create();

        $object->set(SettingJsonObjectKeys::InstalledKey, true);
        $object->set(SettingJsonObjectKeys::ManagementTypeKey, ManagementType::MicroCms->value);

        $object->save();
    }
}
