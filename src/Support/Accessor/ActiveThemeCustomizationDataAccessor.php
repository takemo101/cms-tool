<?php

namespace Takemo101\CmsTool\Support\Accessor;

use ArrayObject;
use CmsTool\Theme\Contract\ThemeCustomizationAccessor;
use LogicException;
use Takemo101\CmsTool\Domain\Theme\ActiveThemeRepository;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ActiveThemeCustomizationDataAccessor
{
    /**
     * constructor
     *
     * @param ActiveThemeRepository $repository
     */
    public function __construct(
        private readonly ActiveThemeRepository $repository,
        private readonly ThemeCustomizationAccessor $accessor,
    ) {
        //
    }

    /**
     * @return ArrayObject
     * @throws LogicException
     */
    public function __invoke(): ArrayObject
    {
        $theme = $this->repository->find();

        if (!$theme) {
            throw new LogicException('The active theme is not found');
        }

        return ImmutableArrayObject::of(
            $this->accessor->load($theme),
        );
    }
}
