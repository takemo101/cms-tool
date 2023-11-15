<?php

namespace CmsTool\Session\Csrf;

use Takemo101\Chubby\Http\Support\AbstractContext;

final class CsrfGuardContext extends AbstractContext
{
    public const ContextKey = '__csrf__';

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
}
