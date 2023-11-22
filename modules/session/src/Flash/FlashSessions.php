<?php

namespace CmsTool\Session\Flash;

use InvalidArgumentException;

final class FlashSessions
{
    /**
     * @var array<class-string<FlashSession>,FlashSession>
     */
    private array $flashSessions = [];

    /**
     * constructor
     *
     * @param FlashSession ...$flashSessions
     */
    public function __construct(
        FlashSession ...$flashSessions,
    ) {
        $this->add(...$flashSessions);
    }

    /**
     * Register flash session classes
     *
     * @param FlashSession ...$flashSessions
     * @return void
     * @throws InvalidArgumentException
     */
    public function add(FlashSession ...$flashSessions): void
    {
        foreach ($flashSessions as $flashSession) {
            $class = get_class($flashSession);

            $this->flashSessions[$class] = $flashSession;
        }
    }

    /**
     * Get flash session
     *
     * @template T of FlashSession
     *
     * @param class-string<T> $class
     * @return T
     * @throws InvalidArgumentException
     */
    public function get(string $class): FlashSession
    {
        /** @var T */
        $flashSession = $this->flashSessions[$class] ?? throw new InvalidArgumentException(
            sprintf(
                'Class %s is not built',
                $class,
            ),
        );

        return $flashSession;
    }

    /**
     * Get all flash sessions
     *
     * @return array<class-string<FlashSession>,FlashSession>
     */
    public function all(): array
    {
        return $this->flashSessions;
    }
}
