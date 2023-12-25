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
     * @param string|null $id
     * @param MicroCmsGetOneQuery|null $apiQuery
     */
    public function __construct(
        private string $endpoint,
        private ?string $id = null,
        private ?MicroCmsGetOneQuery $apiQuery = null,
    ) {
        assert(empty($endpoint) === false, 'endpoint is empty');
    }

    /**
     * {@inheritDoc}
     */
    public function resolveEndpoint(): string
    {
        $id = $this->id;

        return $id
            ? Path::join($this->endpoint, $id)
            : $this->endpoint;
    }

    /**
     * {@inheritDoc}
     */
    protected function defaultQuery(): array
    {
        return $this->apiQuery?->toQuery() ?? [];
    }

    /**
     * Create a request to obtain it by specifying an id from the list format api.
     *
     * @param string $endpoint
     * @param string $id
     * @param MicroCmsGetOneQuery|null $apiQuery
     * @return self
     */
    public static function createOne(
        string $endpoint,
        string $id,
        ?MicroCmsGetOneQuery $apiQuery = null,
    ): self {
        return new self(
            endpoint: $endpoint,
            id: $id,
            apiQuery: $apiQuery,
        );
    }

    /**
     * Create a request to get a single object.
     * No id is required for a single object.
     *
     * @param string $endpoint
     * @param MicroCmsGetOneQuery|null $apiQuery
     * @return self
     */
    public static function createSingle(
        string $endpoint,
        ?MicroCmsGetOneQuery $apiQuery = null,
    ): self {
        return new self(
            endpoint: $endpoint,
            apiQuery: $apiQuery,
        );
    }
}
