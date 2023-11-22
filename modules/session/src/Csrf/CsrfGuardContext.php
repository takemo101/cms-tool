<?php

namespace CmsTool\Session\Csrf;

use Takemo101\Chubby\Http\Support\AbstractContext;

class CsrfGuardContext extends AbstractContext
{
    public const ContextKey = self::class;

    /**
     * constructor
     *
     * @param CsrfGuard $guard
     */
    public function __construct(
        private CsrfGuard $guard,
    ) {
        //
    }

    /**
     * Get CsrfGuard instance.
     *
     * @return CsrfGuard
     */
    public function getGuard(): CsrfGuard
    {
        return $this->guard;
    }

    /**
     * Get contextual data.
     *
     * @return array<string,mixed>
     */
    protected function getServerRequestAttributes(): array
    {
        return [
            CsrfGuard::class => $this->getGuard(),
            CsrfToken::class => $this->getGuard()->getToken(),
        ];
    }
}
