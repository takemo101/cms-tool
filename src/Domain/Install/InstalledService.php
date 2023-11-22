<?php

namespace Takemo101\CmsTool\Domain\Install;

readonly class InstalledService
{
    /**
     * constructor
     *
     * @param InstallRepository $repository
     * @param InstalledSpec $spec
     */
    public function __construct(
        private InstallRepository $repository,
        private InstalledSpec $spec,
    ) {
        //
    }

    /**
     * @return void
     * @throws InstallationNotPossibleException
     */
    public function installed(): void
    {
        if (!$this->spec->isSatisfiedBy()) {
            throw new InstallationNotPossibleException();
        }

        if (!$this->repository->isInstalled()) {
            $this->repository->save(true);
        }
    }
}
