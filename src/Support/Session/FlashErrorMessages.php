<?php

namespace Takemo101\CmsTool\Support\Session;

use CmsTool\Session\Flash\AbstractFlashSession;

class FlashErrorMessages extends AbstractFlashSession
{
    /**
     * @var string
     */
    public const SessionKey = '_errors';

    /**
     * @return array<string,string[]>
     */
    protected function getMessages(): array
    {
        /** @var array<string,string[]> */
        $messages = $this->fetch();

        return $messages;
    }

    /**
     * Get flash error messages from the key
     *
     * @param string $key
     * @return string[]
     */
    public function get(string $key): array
    {
        $messages = $this->getMessages();

        return $messages[$key] ?? [];
    }

    /**
     * Get the first flash error messages from the key
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function first(string $key, ?string $default = null): ?string
    {
        $messages = $this->get($key);

        /** @var string */
        $message = array_shift($messages) ?? $default;

        return $message;
    }

    /**
     * Is there a flash data for the key?
     *
     * @return boolean
     */
    public function has(string $key): bool
    {
        $messages = $this->get($key);

        return !empty($messages);
    }

    /**
     * Obtain all flash data
     *
     * @return array<string,string[]>
     */
    public function all(): array
    {
        return $this->getMessages();
    }

    /**
     * Exists flash error messages?
     *
     * @return boolean
     */
    public function isError(): bool
    {
        return count($this->getMessages()) > 0;
    }
}
