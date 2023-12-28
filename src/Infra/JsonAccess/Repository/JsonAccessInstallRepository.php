<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Install\InstallRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessInstallRepository implements InstallRepository
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
    public function isInstalled(): bool
    {
        $object = $this->creator->create();

        /** @var boolean */
        $installed = $object->get(SettingJsonObjectKeys::InstalledKey, false);

        return (bool) $installed;
    }
}
