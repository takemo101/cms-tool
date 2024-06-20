<?php

namespace Takemo101\CmsTool\Support\FormAppendFilter;

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Session\Csrf\CsrfToken;
use CmsTool\View\Html\Filter\FormAppendFilter;
use Takemo101\Chubby\Context\ContextRepository;

class AppendCsrfInputFilter implements FormAppendFilter
{
    /** @var string[] */
    public const IgnoreMethods = [
        'GET',
        'HEAD',
        'OPTIONS',
    ];

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
     * {@inheritDoc}
     */
    public function filter(array $attributes): ?string
    {
        /** @var string */
        $method = $attributes['method'] ?? 'GET';

        /** @var CsrfGuard|null */
        $guard = $this->repository->get()->get(CsrfGuard::class);

        $token = $guard?->getToken();

        if (
            in_array($method, self::IgnoreMethods)
            || !$token
        ) {
            return null;
        }

        if ($token->isEmpty()) {
            return null;
        }

        return sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            CsrfToken::NameKey,
            $token->name,
        ) . sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            CsrfToken::ValueKey,
            $token->value,
        );
    }
}
