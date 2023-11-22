<?php

namespace Takemo101\CmsTool\Support\Twig;

use Odan\Session\SessionInterface as Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use RuntimeException;

class SessionExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param Session|null $session
     */
    public function __construct(
        private ?Session $session = null,
    ) {
        //
    }

    /**
     * Set the value of session
     *
     * @param Session $session
     * @return void
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * Get the value of session
     *
     * @return Session
     */
    private function getSession(): Session
    {
        $session = $this->session;

        $session ?? throw new RuntimeException('session is not set');

        return $session;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('session', [$this, 'session']),
            new TwigFunction('session_has', [$this, 'sessionHas']),
        ];
    }

    /**
     * Get session from the key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function session(string $key, mixed $default = null): mixed
    {
        return $this->getSession()->get($key, $default);
    }

    /**
     * Is there a session for the key?
     *
     * @param string $key
     * @return boolean
     */
    public function sessionHas(string $key): bool
    {
        return $this->getSession()->has($key);
    }
}
