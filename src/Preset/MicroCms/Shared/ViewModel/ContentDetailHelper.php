<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel;

use Closure;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;
use Takemo101\CmsTool\Support\Shared\HasCamelCaseAccess;

class ContentDetailHelper
{
    use HasCamelCaseAccess;

    /**
     * constructor
     *
     * @param Closure():array{0: ?ImmutableArrayObjectable, 1: ?ImmutableArrayObjectable} $generator [0 => prev, 1 => next]
     */
    public function __construct(
        private readonly Closure $generator,
    ) {
        //
    }

    /**
     * Get the next and previous content.
     *
     * @return object {
     *   prev: ?ImmutableArrayObjectable,
     *   next: ?ImmutableArrayObjectable,
     * }
     */
    public function getPrevNext(): object
    {
        [$prev, $next] = ($this->generator)();

        return new class ($prev, $next) {
            use HasCamelCaseAccess;

            /**
             * constructor
             *
             * @param ImmutableArrayObjectable|null $prev
             * @param ImmutableArrayObjectable|null $next
             */
            public function __construct(
                public readonly ?ImmutableArrayObjectable $prev,
                public readonly ?ImmutableArrayObjectable $next,
            ) {
                //
            }

            /**
             * Check if there is a previous or next content.
             *
             * @return boolean
             */
            public function hasPrevOrNext(): bool
            {
                return $this->prev !== null || $this->next !== null;
            }
        };
    }
}
