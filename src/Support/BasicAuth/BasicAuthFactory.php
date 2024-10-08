<?php

namespace Takemo101\CmsTool\Support\BasicAuth;

use CmsTool\Support\Hash\Hasher;

class BasicAuthFactory
{
    /**
     * constructor
     *
     * @param Hasher $hasher
     * @param BasicAuthHeaderParser $parser
     */
    public function __construct(
        private readonly Hasher $hasher,
        private readonly BasicAuthHeaderParser $parser = new BasicAuthHeaderParser(),
    ) {
        //
    }

    /**
     * Create a new BasicAuth middleware.
     *
     * @param BasicAuthUsers $users
     * @param string $realm
     * @param boolean $enabled
     * @return BasicAuth
     */
    public function create(
        BasicAuthUsers $users,
        string $realm = 'Web',
        bool $enabled = false,
    ): BasicAuth {
        return new BasicAuth(
            authenticator: new BasicAuthenticator(
                users: $users,
                hasher: $this->hasher,
            ),
            parser: $this->parser,
            realm: $realm,
            enabled: $enabled,
        );
    }
}
