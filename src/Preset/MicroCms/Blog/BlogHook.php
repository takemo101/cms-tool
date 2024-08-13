<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use CmsTool\Support\Feed\FeedGenerator;
use CmsTool\Theme\Hook\ThemeHook;
use CmsTool\Theme\Theme;
use CmsTool\View\Accessor\DataAccessors;
use CmsTool\View\HtmlObject;
use Psr\Container\ContainerInterface;
use Takemo101\Chubby\Hook\Hook;
use Takemo101\CmsTool\Preset\MicroCms\Shared\Accessor\TaxonomiesAccessor;
use Takemo101\CmsTool\Support\Htmlable\HeadHtmls;
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
        $hook
            ->onTyped(
                function (
                    HeadHtmls $htmls,
                    ContainerInterface $container,
                ) {
                    /** @var FeedGenerator */
                    $generator = $container->get(FeedGenerator::class);

                    $htmls->add(
                        new HtmlObject(
                            sprintf(
                                '<link rel="alternate" type="%s" href="%s">',
                                $generator->getOutputMeta()->mimeType,
                                route('blog.feed'),
                            ),
                        ),
                    );
                },
            )
            ->onTyped(
                function (
                    DataAccessors $accessors,
                ) use ($theme) {
                    $extension = ImmutableArrayObject::of($theme->meta->extension);

                    /**
                     * @var object{
                     *  blog: string,
                     *  category: string,
                     *  tag: string,
                     * }
                     */
                    $endpoints = ImmutableArrayObject::of([
                        'blog' => $extension->get('endpoints.blog', 'blogs'),
                        'category' => $extension->get('endpoints.category', 'categories'),
                        'tag' => $extension->get('endpoints.tag', 'tags'),
                    ]);

                    /**
                     * @var object{
                     *  category: string,
                     *  tag: string,
                     * }
                     */
                    $fields = ImmutableArrayObject::of([
                        'category' => $extension->get('fields.category', 'category'),
                        'tag' => $extension->get('fields.tag', 'tags'),
                    ]);

                    $accessors
                        ->add(
                            [
                                'contents',
                                'contents_*',
                            ],
                            TaxonomiesAccessor::class,
                            [
                                'endpoint' => $endpoints->blog,
                                'orders' => '-publishedAt',
                            ]
                        )
                        ->add(
                            [
                                'category_contents',
                                'category_contents_*',
                            ],
                            TaxonomiesAccessor::class,
                            [
                                'endpoint' => $endpoints->blog,
                                'format' => "{$fields->category}[equals]%s",
                            ]
                        )
                        ->add(
                            [
                                'tag_contents',
                                'tag_contents_*',
                            ],
                            TaxonomiesAccessor::class,
                            [
                                'endpoint' => $endpoints->blog,
                                'format' => "{$fields->tag}[equals]%s",
                            ]
                        )
                        ->add(
                            [
                                'categories',
                                'categories_*',
                            ],
                            TaxonomiesAccessor::class,
                            [
                                'endpoint' => $endpoints->category,
                            ]
                        )
                        ->add(
                            [
                                'tags',
                                'tags_*',
                            ],
                            TaxonomiesAccessor::class,
                            [
                                'endpoint' => $endpoints->tag,
                            ]
                        )
                        ->add(
                            'related_contents',
                            RelatedBlogAccessor::class,
                            [
                                'endpoint' => $endpoints->blog,
                                'fields' => $fields,
                            ]
                        );
                },
            );
    }
}
