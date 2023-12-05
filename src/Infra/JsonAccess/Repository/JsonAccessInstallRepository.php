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

        if (!$object->has(SettingJsonObjectKeys::InstalledKey)) {
            return false;
        }

        /** @var boolean */
        $installed = $object->get(SettingJsonObjectKeys::InstalledKey);

        return (bool) $installed;
    }

    /**
     * {@inheritDoc}
     */
    public function save(bool $installed): void
    {
        $object = $this->creator->create();

        $object->set(SettingJsonObjectKeys::InstalledKey, $installed);

        $object->save();
    }
}
