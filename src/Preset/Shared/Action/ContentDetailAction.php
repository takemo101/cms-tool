<?php

namespace Takemo101\CmsTool\Preset\Shared\Action;

use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Preset\Shared\ViewModel\ContentDetailPage;
use Takemo101\CmsTool\UseCase\MicroCms\QueryService\Content\MicroCmsContentQueryService;

class ContentDetailAction
{
    /** @var string */
    public const DraftQueryKey = 'key';

    /**
     * constructor
     *
     * @param string $endpoint
     * @param string $view
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly string $view,
    ) {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @param MicroCmsContentQueryService $queryService
     * @param string $id
     * @return View
     * @throws HttpNotFoundException
     */
    public function __invoke(
        ServerRequestInterface $request,
        MicroCmsContentQueryService $queryService,
        string $id,
    ): View {
        $params = $request->getQueryParams();

        /** @var ?string */
        $draftKey = $params[self::DraftQueryKey] ?? null;

        $content = $draftKey
            ?  $queryService->getOneDraft(
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

        return view($this->view, new ContentDetailPage(
            content: $content,
            isDraft: !empty($draftKey),
        ));
    }
}
