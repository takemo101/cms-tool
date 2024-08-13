<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Blog;

use CmsTool\Support\Feed\Feed;
use CmsTool\Support\Feed\FeedAuthor;
use CmsTool\Support\Feed\FeedCategories;
use CmsTool\Support\Feed\FeedEnclosure;
use CmsTool\Support\Feed\FeedGenerator;
use CmsTool\Support\Feed\FeedItem;
use CmsTool\Support\Feed\FeedItems;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Takemo101\Chubby\Http\Uri\ApplicationUri;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaData;
use DateTimeImmutable;

/**
 * Generate a feed of blog articles and write it to the response.
 *
 * @phpstan-type ContentData = object{
 *   title: string,
 *   publishedAt?: string,
 *   updatedAt: string,
 *   content: string,
 *   id: string,
 *   eyecatch?: object{
 *     url: string,
 *   },
 *   category?: object{
 *     name: string,
 *   }
 * }
 */
class BlogFeedRenderer implements ResponseRenderer
{
    /**
     * constructor
     *
     * @param SiteMetaData $siteMeta
     * @param ContentData[] $contents
     * @param FeedGenerator $generator
     * @param ApplicationUri $uri
     * @param string $order
     */
    public function __construct(
        private readonly SiteMetaData $siteMeta,
        private readonly array $contents,
        private readonly FeedGenerator $generator,
        private readonly ApplicationUri $uri,
        private readonly string $order = 'publishedAt',
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function render(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {

        /**
         * @var ContentData|null $latest
         */
        $latest = $this->contents[0] ?? null;

        $order = $this->order;

        $siteMeta = $this->siteMeta;

        $updated = new DateTimeImmutable($latest?->{$order} ?? null);

        $items = collect($this->contents)
            ->map(
                /**
                 * @param ContentData $content
                 */
                fn(object $content) => new FeedItem(
                    title: $content->title,
                    published: new DateTimeImmutable($content->{$order} ?? $updated),
                    // If there is a featured image in the content, embed the image instead of the enclosure.
                    content: (
                        $content->eyecatch
                        ? sprintf(
                            '<img src="%s" alt="%s" />',
                            $content->eyecatch->url,
                            $content->title,
                        )
                        : ''
                    ) . $content->content,
                    link: $this->uri->withPath(
                        route('blog.detail', [
                            'id' => $content->id,
                        ]),
                    ),
                    guid: $content->id,
                    author: new FeedAuthor(
                        name: $siteMeta->name,
                    ),
                    enclosure: $content->eyecatch
                        ? new FeedEnclosure(
                            url: $content->eyecatch->url,
                        )
                        : null,
                    categories: $content->category
                        ? new FeedCategories(
                            $content->category->name,
                        )
                        : new FeedCategories(),
                ),
            )
            ->all();

        $response = $response->withHeader(
            'Content-Type',
            $this->generator->getOutputMeta()
                ->contentType
        );

        $response->getBody()->write(
            $this->generator->generate(
                new Feed(
                    title: $siteMeta->seo->title ?: $siteMeta->name,
                    description: $siteMeta->seo->description ?: $siteMeta->seo->title ?: $siteMeta->name,
                    link: $this->uri->getBase(),
                    updated: $updated,
                    copyright: $siteMeta->name,
                    items: new FeedItems(...$items),
                ),
            ),
        );

        return $response;
    }
}
