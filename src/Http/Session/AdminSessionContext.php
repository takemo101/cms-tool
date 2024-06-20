<?php

namespace Takemo101\CmsTool\Http\Session;

use Takemo101\Chubby\Http\Context\AbstractContext;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;

class AdminSessionContext extends AbstractContext
{
    /** @var string */
    public const ContextKey = self::class;

    /**
     * constructor
     *
     * @param AdminSession $session
     */
    public function __construct(
        private readonly AdminSession $session,
    ) {
        //
    }

    /**
     * Get the session object.
     *
     * @return AdminSession
     */
    public function getAdminSession(): AdminSession
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
            AdminSession::class => $this->session,
        ];
    }
}
