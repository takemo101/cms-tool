<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Ping;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class MicroCmsPingRequest extends Request
{
    protected Method $method = Method::GET;

    protected ?string $response = MicroCmsPingResponse::class;

    /**
     * {@inheritDoc}
     */
    public function resolveEndpoint(): string
    {
        return '/ping';
    }
}
