<?php

namespace Takemo101\CmsTool\Infra\Saloon\HttpClient\Ping;

use Saloon\Http\Response;

class MicroCmsPingResponse extends Response
{
    public const SuccessBody = '{}';

    /**
     * Whether the API key and service ID are valid
     * @return boolean
     */
    public function isValid(): bool
    {
        return $this->body() === self::SuccessBody;
    }
}
