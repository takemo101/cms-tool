<?php

namespace CmsTool\Session\Flash;

use Odan\Session\SessionInterface as Session;
use InvalidArgumentException;

final class FlashSessionsFactory
{
    /**
     * @var class-string<FlashSession>[]
     */
    private array $classes = [];

    /**
     * constructor
     *
     * @param class-string<FlashSession> ...$classes
     */
    public function __construct(
        string ...$classes,
    ) {
        $this->register(...$classes);
    }

    /**
     * Register flash session classes
     *
     * @param class-string<FlashSession> ...$classes
     * @return void
     * @throws InvalidArgumentException
     */
    public function register(string ...$classes): void
    {
        foreach ($classes as $class) {
            if (!is_subclass_of($class, FlashSession::class)) {
                throw new InvalidArgumentException(sprintf(
                    'Class %s must be a subclass of %s',
                    $class,
                    FlashSession::class,
                ));
            }

            $this->classes[] = $class;
        }
    }

    /**
     * Create flash sessions
     *
     * @param Session $session
     * @return FlashSessions
     */
    public function create(Session $session): FlashSessions
    {
        /** @var FlashSession[] */
        $result = [];

        foreach ($this->classes as $class) {
            $result[] = $class::fromSession($session);
        }

        return new FlashSessions(...$result);
    }
}
