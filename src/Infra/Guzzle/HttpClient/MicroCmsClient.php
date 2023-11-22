<?php

namespace Takemo101\CmsTool\Infra\Guzzle\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApi;

class MicroCmsClient
{
    public const TokenKey = 'X-MICROCMS-API-KEY';

    /**
     * constructor
     *
     * @param string $key
     * @param string $serviceId
     * @param ClientInterface $client
     */
    public function __construct(
        private string $key,
        private string $serviceId,
        private ClientInterface $client = new Client(),
    ) {
        //
    }

    /**
     * Check if the access key and service ID are valid
     *
     * @return bool
     */
    public function ping(): bool
    {
        $response = $this->client->request(
            'GET',
            'ping',
            [
                'base_uri' => $this->createBaseUrl(),
                'headers' => $this->createHeaders(),
                'http_errors' => false,
            ]
        );

        $contents = $response->getBody()->getContents();

        return !empty($contents);
    }

    /**
     * @return string
     */
    private function createBaseUrl(): string
    {
        return "https://{$this->serviceId}.microcms.io/api/v1/";
    }

    /**
     * @return array<string,string>
     */
    private function createHeaders(): array
    {
        return [
            self::TokenKey => $this->key,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Create a new instance from the entity
     *
     * @param MicroCmsApi $entity
     * @param ClientInterface $client
     * @return self
     */
    public static function fromEntity(
        MicroCmsApi $entity,
        ClientInterface $client = new Client(),
    ): static {
        return new self(
            key: $entity->key,
            serviceId: $entity->serviceId,
            client: $client,
        );
    }
}
