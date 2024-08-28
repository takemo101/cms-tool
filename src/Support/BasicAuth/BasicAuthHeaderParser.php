<?php

namespace Takemo101\CmsTool\Support\BasicAuth;

use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;

/**
 * @phpstan-type UserData = ImmutableArrayObjectable<string,mixed>&object{
 *    username: string,
 *   password: string,
 * }
 */
class BasicAuthHeaderParser
{
    /**
     * Parse the header.
     * If the header is invalid, it returns false.
     *
     * @param string $header
     * @return UserData|false
     */
    public function parse(string $header): object|false
    {
        // If the header does not start with 'Basic', it is invalid.
        if (strpos($header, 'Basic') !== 0) {
            return false;
        }

        // Remove 'Basic' from the header.
        $header = base64_decode(substr($header, 6));

        // If the header is invalid, it returns false.
        if ($header === false) {
            return false;
        }

        // Split the header into username and password.
        $splitHeader = explode(':', $header, 2);

        if (count($splitHeader) !== 2) {
            return false;
        }

        /** @var UserData */
        $data = ImmutableArrayObject::of([
            'username' => $splitHeader[0],
            'password' => $splitHeader[1],
        ]);

        return $data;
    }

    /**
     * Parse the header from the request.
     * If the header is invalid, it returns false.
     *
     * @param ServerRequestInterface $request
     * @return UserData|false
     */
    public function parseFromRequest(ServerRequestInterface $request): object|false
    {
        $header = $request->getHeaderLine('Authorization');

        return $this->parse($header);
    }
}
