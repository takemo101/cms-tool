<?php

namespace CmsTool\Session\Flash;

use Takemo101\Chubby\Http\Support\AbstractContext;

final class FlashSessionsContext extends AbstractContext
{
    public const ContextKey = self::class;

    /**
     * constructor
     *
     * @param FlashSessions $flashSessions
     */
    public function __construct(
        private FlashSessions $flashSessions,
    ) {
        //
    }

    /**
     * Get FlashSessions instance.
     *
     * @return FlashSessions
     */
    public function getFlashSessions(): FlashSessions
    {
        return $this->flashSessions;
    }

    /**
     * Get contextual data.
     *
     * @return array<string,mixed>
     */
    protected function getServerRequestAttributes(): array
    {
        return [
            FlashSessions::class => $this->getFlashSessions(),
            ...$this->getFlashSessions()->all(),
        ];
    }
}
