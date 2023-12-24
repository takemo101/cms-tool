<?php

namespace Takemo101\CmsTool\Preset\Blog;

use CmsTool\Theme\Hook\ThemeHook;
use CmsTool\Theme\Theme;
use CmsTool\View\Accessor\DataAccessors;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Preset\Shared\Accessor\TaxonomiesAccessor;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class BlogHook implements ThemeHook
{
    /**
     * {@inheritDoc}
     */
    public function hook(
        Theme $theme,
        Hook $hook,
    ): void {
        $hook->onByType(
            function (
                DataAccessors $accessors,
            ) use ($theme) {
                $extension = ImmutableArrayObject::of($theme->setting->extension);

                /** @var string */
                $categoryEndpoint = $extension->get('category.endpoint', 'categories');

                /** @var string */
                $tagEndpoint = $extension->get('tag.endpoint', 'tags');

                $accessors
                    ->add(
                        'categories',
                        TaxonomiesAccessor::class,
                        [
                            'theme' => $theme,
                            'endpoint' => $categoryEndpoint,
                        ]
                    )
                    ->add(
                        'tags',
                        TaxonomiesAccessor::class,
                        [
                            'theme' => $theme,
                            'endpoint' => $tagEndpoint,
                        ]
                    );
            },
        );
    }
}
