<?php

namespace CmsTool\Session\Flash;

use Odan\Session\SessionInterface as Session;

abstract class AbstractFlashSession implements FlashSession
{
    /**
     * If you attach _ to the prefix, it will be excluded from the check of CsrfGuard.
     *
     * @var string
     */
    public const SessionKey = '_';

    /**
     * @var mixed[]|null
     */
    private ?array $data = null;

    /**
     * constructor
     *
     * @param Session $session
     */
    final public function __construct(
        protected Session $session,
    ) {
        //
    }

    /**
     * Set the value of flash error messages
     *
     * @param mixed[] $data
     * @return void
     */
    public function put(array $data): void
    {
        $this->session->set(static::SessionKey, $data);
    }

    /**
     * Fetch session data
     *
     * @return mixed[]
     */
    protected function fetch(): array
    {
        $data = $this->data;

        if (is_null($data)) {
            $data = $this->session->get(static::SessionKey, []);

            if (!is_array($data)) {
                $data = [];
            }

            $this->session->delete(static::SessionKey);

            return $this->data = $data;
        }

        return $data;
    }

    /**
     * Create a new instance from session
     *
     * @param Session $session
     * @return static
     */
    public static function fromSession(Session $session): static
    {
        return new static($session);
    }
}
