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
        $hook->onTyped(
            function (
                DataAccessors $accessors,
            ) use ($theme) {
                $extension = ImmutableArrayObject::of($theme->meta->extension);

                /** @var string */
                $blogEndpoint = $extension->get('endpoints.blog', 'blogs');

                /** @var string */
                $categoryEndpoint = $extension->get('endpoints.category', 'categories');

                /** @var string */
                $tagEndpoint = $extension->get('endpoints.tag', 'tags');

                $accessors
                    ->add(
                        [
                            'contents',
                            'contents_*',
                        ],
                        TaxonomiesAccessor::class,
                        [
                            'theme' => $theme,
                            'endpoint' => $blogEndpoint,
                        ]
                    )
                    ->add(
                        [
                            'categories',
                            'categories_*',
                        ],
                        TaxonomiesAccessor::class,
                        [
                            'theme' => $theme,
                            'endpoint' => $categoryEndpoint,
                        ]
                    )
                    ->add(
                        [
                            'tags',
                            'tags_*',
                        ],
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
