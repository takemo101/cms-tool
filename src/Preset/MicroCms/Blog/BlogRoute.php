<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use CmsTool\Theme\Routing\ThemeRoute;
use CmsTool\Theme\Theme;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Takemo101\CmsTool\Preset\MicroCms\Shared\Action\ContentDetailAction;
use Takemo101\CmsTool\Preset\MicroCms\Shared\Action\ContentIndexAction;
use Takemo101\CmsTool\Preset\MicroCms\Shared\Action\TaxonomyIndexAction;
use Takemo101\CmsTool\Preset\MicroCms\Shared\Action\TaxonomyIndexActionEndpoints;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;

/**
 * @phpstan-type ExtensionData = ImmutableArrayObjectable<string,mixed>&object{
 *  endpoints: object{
 *   blog: string,
 *   category: string,
 *   tag: string,
 *  },
 *  signatures: object{
 *   blog: string,
 *   category: string,
 *   tag: string,
 *  },
 *  fields: object{
 *   category: string,
 *   tag: string,
 *  }
 * }
 */
class BlogRoute implements ThemeRoute
{
    /**
     * {@inheritDoc}
     */
    public function route(
        Theme $theme,
        RouteCollectorProxyInterface $proxy,
    ): void {
        $object = ImmutableArrayObject::of(
            $theme->meta->extension,
        );

        $order = 'publishedAt';

        /**
         * @var ExtensionData
         */
        $ext = ImmutableArrayObject::of([
            'endpoints' => [
                'blog' => $object->get('endpoints.blog', 'blogs'),
                'category' => $object->get('endpoints.category', 'categories'),
                'tag' => $object->get('endpoints.tag', 'tags'),
            ],
            'signatures' => [
                'blog' => $object->get('signatures.blog', 'blog'),
                'category' => $object->get('signatures.category', 'category'),
                'tag' => $object->get('signatures.tag', 'tag'),
            ],
            'fields' => [
                'category' => $object->get('fields.category', 'category'),
                'tag' => $object->get('fields.tag', 'tags'),
            ],
        ]);

        $proxy->get(
            "/{$ext->signatures->blog}",
            new ContentIndexAction(
                endpoint: $ext->endpoints->blog,
                signature: $ext->signatures->blog,
                order: "-{$order}",
            ),
        )
            ->setName('blog.index');

        $proxy->get(
            "/{$ext->signatures->blog}/{id}",
            new ContentDetailAction(
                endpoint: $ext->endpoints->blog,
                signature: $ext->signatures->blog,
                orderField: $order,
            ),
        )
            ->setName('blog.detail');

        $proxy->get(
            "/{$ext->signatures->category}/{id}",
            new TaxonomyIndexAction(
                endpoints: new TaxonomyIndexActionEndpoints(
                    taxonomy: $ext->endpoints->category,
                    content: $ext->endpoints->blog,
                ),
                relation: $ext->fields->category,
                signature: $ext->signatures->category,
            ),
        )
            ->setName('blog.category');

        $proxy->get(
            "/{$ext->signatures->tag}/{id}",
            new TaxonomyIndexAction(
                endpoints: new TaxonomyIndexActionEndpoints(
                    taxonomy: $ext->endpoints->tag,
                    content: $ext->endpoints->blog,
                ),
                relation: $ext->fields->tag,
                signature: $ext->signatures->tag,
                multiple: true,
            ),
        )
            ->setName('blog.tag');
    }
}
