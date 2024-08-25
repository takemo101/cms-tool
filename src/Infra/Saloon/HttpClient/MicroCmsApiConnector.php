<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient;

use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\HasTimeout;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Infra\Shared\Exception\InfraException;

class MicroCmsApiConnector extends Connector
{
    use HasTimeout;

    /** @var string */
    public const TokenKey = 'X-MICROCMS-API-KEY';

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    /**
     * constructor
     *
     * @param string $key
     * @param string $serviceId
     * @param array<string,mixed> $guzzleOptions
     */
    public function __construct(
        private readonly string $key,
        private readonly string $serviceId,
        private readonly array $guzzleOptions = [],
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function resolveBaseUrl(): string
    {
        return "https://{$this->serviceId}.microcms.io/api/v1/";
    }

    /**
     * {@inheritDoc}
     */
    protected function defaultHeaders(): array
    {
        return [
            self::TokenKey => $this->key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Default Config
     *
     * @return array<string, mixed>
     */
    protected function defaultConfig(): array
    {
        return $this->guzzleOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        /** @var array<string,mixed> */
        $data = $response->json();

        if (!is_array($data)) {
            throw new InfraException('Response data is not array');
        }

        return ImmutableArrayObject::of($data);
    }

    /**
     * Create a MicroCmsClient instance from entity
     *
     * @param MicroCmsApi $api
     * @param array<string,mixed> $guzzleOptions
     * @return self
     */
    public static function fromEntity(
        MicroCmsApi $api,
        array $guzzleOptions = [],
    ): self {
        return new self(
            key: $api->key,
            serviceId: $api->serviceId,
            guzzleOptions: $guzzleOptions,
        );
    }
}
