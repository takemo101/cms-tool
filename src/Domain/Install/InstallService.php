<?php

namespace Takemo101\CmsTool\Domain\Install;

class InstallService
{
    /**
     * constructor
     *
     * @param InstallRepository $repository
     * @param InstallSpec $spec
     */
    public function __construct(
        private InstallRepository $repository,
        private InstallSpec $spec,
    ) {
        //
    }

    /**
     * @return void
     * @throws InstallationNotPossibleException
     */
    public function install(): void
    {
        if (!$this->spec->isSatisfiedBy()) {
            throw new InstallationNotPossibleException();
        }

        if (!$this->repository->isInstalled()) {
            $this->repository->save(true);
        }
    }
}
