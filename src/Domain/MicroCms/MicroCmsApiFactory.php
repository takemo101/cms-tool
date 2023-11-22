<?php

namespace Takemo101\CmsTool\Domain\MicroCms;

class MicroCmsApiFactory
{
    /**
     * constructor
     *
     * @param MicroCmsApiAccessValidator $validator
     */
    public function __construct(
        private MicroCmsApiAccessValidator $validator,
    ) {
        //
    }

    /**
     * create
     *
     * @param string $key
     * @param string $serviceId
     * @return MicroCmsApi
     * @throws MicroCmsApiAccessException
     */
    public function create(
        string $key,
        string $serviceId,
    ): MicroCmsApi {
        $entity = new MicroCmsApi(
            key: $key,
            serviceId: $serviceId,
        );

        if (!$this->validator->validate($entity)) {
            throw new MicroCmsApiAccessException();
        }

        return $entity;
    }
}
