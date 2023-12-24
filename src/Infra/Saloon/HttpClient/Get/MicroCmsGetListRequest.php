<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Get;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class MicroCmsGetListRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * constructor
     *
     * @param string $endpoint
     * @param MicroCmsGetListQuery|null $apiQuery
     */
    public function __construct(
        private string $endpoint,
        private ?MicroCmsGetListQuery $apiQuery = null,
    ) {
        assert(empty($endpoint) === false, 'endpoint is empty');
    }

    /**
     * {@inheritDoc}
     */
    public function resolveEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * {@inheritDoc}
     */
    protected function defaultQuery(): array
    {
        return $this->apiQuery?->toQuery() ?? [];
    }
}
