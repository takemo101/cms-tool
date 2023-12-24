<?php

namespace Takemo101\CmsTool\Preset\Blog;

use CmsTool\Theme\Routing\ThemeRoute;
use CmsTool\Theme\Theme;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Takemo101\CmsTool\Preset\Shared\Action\ContentDetailAction;
use Takemo101\CmsTool\Preset\Shared\Action\ContentIndexAction;
use Takemo101\CmsTool\Preset\Shared\Action\TaxonomyIndexAction;
use Takemo101\CmsTool\Support\Shared\ImmutableArrayObject;

class BlogRoute implements ThemeRoute
{
    /**
     * {@inheritDoc}
     */
    public function route(
        Theme $theme,
        RouteCollectorProxyInterface $proxy,
    ): void {

        $extension = ImmutableArrayObject::of($theme->setting->extension);

        /** @var string */
        $blogSignature = $extension->get('blog.signature', 'blog');
        /** @var string */
        $blogEndpoint = $extension->get('blog.endpoint', 'blogs');

        /** @var string */
        $categorySignature = $extension->get('category.signature', 'category');
        /** @var string */
        $categoryEndpoint = $extension->get('category.endpoint', 'categories');
        /** @var string */
        $categoryContentField = $extension->get('category.content_field', 'category');

        /** @var string */
        $tagSignature = $extension->get('tag.signature', 'tag');
        /** @var string */
        $tagEndpoint = $extension->get('tag.endpoint', 'tags');
        /** @var string */
        $tagContentField = $extension->get('tag.content_field', 'tags');


        $proxy->get(
            "/{$blogSignature}",
            new ContentIndexAction(
                endpoint: $blogEndpoint,
                view: "pages.{$blogSignature}.index",
            ),
        )
            ->setName('blog.index');

        $proxy->get(
            "/{$blogSignature}/{id}",
            new ContentDetailAction(
                endpoint: $blogEndpoint,
                view: "pages.{$blogSignature}.detail",
            ),
        )
            ->setName('blog.detail');

        $proxy->get(
            "/{$categorySignature}/{id}",
            new TaxonomyIndexAction(
                taxonomyEndpoint: $categoryEndpoint,
                contentEndpoint: $blogEndpoint,
                relation: $categoryContentField,
                view: "pages.{$categorySignature}",
            ),
        )
            ->setName('blog.category');

        $proxy->get(
            "/{$tagSignature}/{id}",
            new TaxonomyIndexAction(
                taxonomyEndpoint: $tagEndpoint,
                contentEndpoint: $blogEndpoint,
                relation: $tagContentField,
                view: "pages.{$tagSignature}",
                multiple: true,
            ),
        )
            ->setName('blog.tag');
    }
}
