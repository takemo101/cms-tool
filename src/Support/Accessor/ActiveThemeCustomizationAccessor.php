<?php

namespace Takemo101\CmsTool\Support\Accessor;

use ArrayObject;
use CmsTool\Theme\ActiveTheme;
use CmsTool\Theme\Contract\ThemeCustomizationAccessor;
use Takemo101\Chubby\Context\ContextRepository;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class ActiveThemeCustomizationAccessor
{
    public const ThemeCustomizationPreviewContextKey = 'theme.customization.preview';

    /**
     * constructor
     *
     * @param ContextRepository $contextRepository
     * @param ActiveTheme $activeTheme
     * @param ThemeCustomizationAccessor $accessor
     */
    public function __construct(
        private readonly ContextRepository $contextRepository,
        private readonly ActiveTheme $activeTheme,
        private readonly ThemeCustomizationAccessor $accessor,
    ) {
        //
    }

    /**
     * @return ArrayObject
     */
    public function __invoke(): ArrayObject
    {
        $context = $this->contextRepository->get();

        // If the context has the theme customization data, use it.
        if ($context->has(self::ThemeCustomizationPreviewContextKey)) {
            /** @var array<string,array<string,mixed>> */
            $data = $context->get(self::ThemeCustomizationPreviewContextKey, []);

            return ImmutableArrayObject::of($data);
        }

        return ImmutableArrayObject::of(
            $this->accessor->load($this->activeTheme),
        );
    }
}
