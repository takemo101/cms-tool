<?php

namespace CmsTool\Session;

use Odan\Session\FlashInterface as Flash;
use Odan\Session\SessionInterface as Session;
use Takemo101\Chubby\Http\Context\AbstractContext;

class SessionContext extends AbstractContext
{
    public const ContextKey = self::class;

    /**
     * constructor
     *
     * @param Session $session
     */
    public function __construct(
        private readonly Session $session,
    ) {
        //
    }

    /**
     * Get Session instance.
     *
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Get contextual data.
     *
     * @return array<string,mixed>
     */
    protected function getContextValues(): array
    {
        return [
            Session::class => $this->getSession(),
            Flash::class => $this->getSession()->getFlash(),
        ];
    }
}
