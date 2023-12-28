<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Install\Uninstaller;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessUninstaller implements Uninstaller
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
    public function uninstall(): void
    {
        $object = $this->creator->create();

        $object->change([
            SettingJsonObjectKeys::InstalledKey => false,
        ]);

        $object->save();
    }
}
