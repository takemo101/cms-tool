<?php

namespace CmsTool\Session;

use Odan\Session\SessionInterface as Session;
use Odan\Session\SessionManagerInterface as SessionManager;
use CmsTool\Session\Contract\SessionFactory;
use DI\Attribute\Inject;
use Odan\Session\PhpSession;

class NativePhpSessionFactory implements SessionFactory
{
    /**
     * constructor
     *
     * @param array<string,mixed> $options
     */
    public function __construct(
        #[Inject('config.session.options')]
        private array $options = [],
    ) {
        //
    }

    /**
     * Set session options.
     *
     * @param array<string,mixed> $options default {
     *  id: null,
     *  name: 'app',
     *  lifetime: 7200,
     *  path: null,
     *  domain: null,
     *  secure: false,
     *  httponly: true,
     *  cache_limiter: 'nocache',
     * }
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Create session.
     *
     * @return Session & SessionManager
     */
    public function create(): Session & SessionManager
    {
        return new PhpSession($this->options);
    }
}
