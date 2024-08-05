<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\Action;

use ArrayObject;
use Closure;
use CmsTool\View\View;
use CmsTool\View\ViewCreator;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Preset\Shared\Exception\NotFoundThemeTemplateException;
use Takemo101\CmsTool\Preset\Shared\LayeredTemplateNamesCreator;
use Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel\ContentDetailPage;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentGetListQuery;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;

class ContentDetailAction
{
    /** @var string */
    public const DraftQueryKey = 'key';

    /**
     * constructor
     *
     * @param string $endpoint
     * @param string $signature
     * @param string $orderField The field name for the basic sorting order of the content
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly string $signature,
        private readonly string $orderField = 'publishedAt',
    ) {
        assert(
            empty($endpoint) === false,
            'The endpoint must not be empty',
        );

        assert(
            empty($signature) === false,
            'The signature must not be empty',
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param MicroCmsContentQueryService $queryService
     * @param ViewCreator $creator
     * @param LayeredTemplateNamesCreator $names
     * @param string $id
     * @return View
     * @throws HttpNotFoundException|NotFoundThemeTemplateException
     */
    public function __invoke(
        ServerRequestInterface $request,
        MicroCmsContentQueryService $queryService,
        ViewCreator $creator,
        LayeredTemplateNamesCreator $names,
        string $id,
    ): View {
        $params = $request->getQueryParams();

        /** @var ?string */
        $draftKey = $params[self::DraftQueryKey] ?? null;

        $content = $draftKey
            ? $queryService->getOneDraft(
                endpoint: $this->endpoint,
                id: $id,
                draftKey: $draftKey,
            )
            : $queryService->getOne(
                endpoint: $this->endpoint,
                id: $id,
            );

        if (!$content) {
            throw new HttpNotFoundException(
                request: $request,
                message: 'Content not found',
            );
        }

        $templateNames = $names->detail($this->signature, $id);

        return $creator->createIfExists(
            $templateNames,
            new ContentDetailPage(
                content: $content,
                prevAndNextContentsGenerator: $this->createPrevAndNextGenerator($queryService, $content),
                isDraft: !empty($draftKey),
            )
        ) ?? throw new NotFoundThemeTemplateException($templateNames);
    }

    /**
     * Create a closure to retrieve the next and previous content.
     *
     * @param MicroCmsContentQueryService $queryService
     * @param ArrayObject $content
     * @return Closure():array{0: ?ArrayObject, 1: ?ArrayObject}
     */
    private function createPrevAndNextGenerator(
        MicroCmsContentQueryService $queryService,
        ArrayObject $content,
    ): Closure {
        return function () use ($queryService, $content) {
            /** @var string */
            $value = $content->{$this->orderField};
            $field = $this->orderField;

            return [
                $queryService->getFirst(
                    endpoint: $this->endpoint,
                    query: new MicroCmsContentGetListQuery(
                        orders: "-{$field}",
                        filters: "{$field}[less_than]{$value}",
                    ),
                ),
                $queryService->getFirst(
                    endpoint: $this->endpoint,
                    query: new MicroCmsContentGetListQuery(
                        orders: $field,
                        filters: "{$field}[greater_than]{$value}",
                    ),
                ),
            ];
        };
    }
}
