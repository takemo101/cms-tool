<?php

namespace Takemo101\CmsTool\Support\Twig;

use Odan\Session\SessionInterface as Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Takemo101\Chubby\Context\ContextRepository;

class SessionExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param ContextRepository $repository
     */
    public function __construct(
        private readonly ContextRepository $repository,
    ) {
        //
    }

    /**
     * Get the value of session
     *
     * @return Session
     */
    private function getSession(): Session
    {
        return $this->repository->get()
            ->getTyped(Session::class);
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('session', $this->session(...)),
            new TwigFunction('session_has', $this->sessionHas(...)),
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
