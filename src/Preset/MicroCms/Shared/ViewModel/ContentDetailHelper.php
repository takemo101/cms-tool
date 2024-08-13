<?php

namespace Takemo101\CmsTool\Preset\MicroCms\Shared\ViewModel;

use ArrayObject;
use Closure;
use Takemo101\CmsTool\Support\Shared\HasCamelCaseAccess;

class ContentDetailHelper
{
    use HasCamelCaseAccess;

    /**
     * constructor
     *
     * @param Closure():array{0: ?ArrayObject, 1: ?ArrayObject} $generator [0 => prev, 1 => next]
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
     *   prev: ?ArrayObject,
     *   next: ?ArrayObject,
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
             * @param ArrayObject|null $prev
             * @param ArrayObject|null $next
             */
            public function __construct(
                public readonly ?ArrayObject $prev,
                public readonly ?ArrayObject $next,
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
