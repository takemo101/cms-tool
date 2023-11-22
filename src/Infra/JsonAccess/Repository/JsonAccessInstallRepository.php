<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Install\InstallRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;

readonly class JsonAccessInstallRepository implements InstallRepository
{
    public const ObjectKey = 'installed';

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
     * @return boolean
     */
    public function isInstalled(): bool
    {
        $object = $this->creator->create();

        if (!$object->has(self::ObjectKey)) {
            return false;
        }

        /** @var boolean */
        $installed = $object->get(self::ObjectKey);

        return (bool) $installed;
    }

    /**
     * Save the installation status
     *
     * @return void
     */
    public function save(bool $installed): void
    {
        $object = $this->creator->create();

        $object->set(self::ObjectKey, $installed);

        $object->save();
    }
}
