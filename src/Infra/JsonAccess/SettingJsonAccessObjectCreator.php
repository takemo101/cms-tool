<?php

namespace Takemo101\CmsTool\Infra\JsonAccess;

use CmsTool\Support\JsonAccess\JsonAccessObject;
use CmsTool\Support\JsonAccess\JsonAccessObjectCreator;
use DI\Attribute\Inject;

readonly class SettingJsonAccessObjectCreator
{
    /**
     * constructor
     *
     * @param JsonAccessObjectCreator $creator
     * @param string $settingPath
     */
    public function __construct(
        private JsonAccessObjectCreator $creator,
        #[Inject('config.system.setting')]
        private string $settingPath,
    ) {
        //
    }

    /**
     * Create jsonAccessObject of setting.json
     *
     * @return JsonAccessObject
     */
    public function create(): JsonAccessObject
    {
        $object = $this->creator->create($this->settingPath);

        return $object;
    }
}
