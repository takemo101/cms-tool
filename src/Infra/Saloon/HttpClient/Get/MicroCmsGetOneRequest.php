<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Get;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Symfony\Component\Filesystem\Path;

class MicroCmsGetOneRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * constructor
     *
     * @param string $endpoint
     * @param string $id
     * @param MicroCmsGetOneQuery|null $apiQuery
     */
    public function __construct(
        private string $endpoint,
        private string $id,
        private ?MicroCmsGetOneQuery $apiQuery = null,
    ) {
        assert(empty($endpoint) === false, 'endpoint is empty');
        assert(empty($id) === false, 'id is empty');
    }

    /**
     * {@inheritDoc}
     */
    public function resolveEndpoint(): string
    {
        return Path::join($this->endpoint, $this->id);
    }

    /**
     * {@inheritDoc}
     */
    protected function defaultQuery(): array
    {
        return $this->apiQuery?->toQuery() ?? [];
    }
}
