<?php

namespace Takemo101\CmsTool\Support\Twig;

use Odan\Session\FlashInterface as Flash;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use RuntimeException;

class FlashExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param Flash|null $flash
     */
    public function __construct(
        private ?Flash $flash = null,
    ) {
        //
    }

    /**
     * Set the value of flash
     *
     * @param Flash $flash
     * @return void
     */
    public function setFlash(Flash $flash): void
    {
        $this->flash = $flash;
    }

    /**
     * Get the value of flash
     *
     * @return Flash
     */
    private function getFlash(): Flash
    {
        $flash = $this->flash;

        $flash ?? throw new RuntimeException('flash is not set');

        return $flash;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'flash']),
            new TwigFunction('flash_has', [$this, 'flashHas']),
        ];
    }

    /**
     * Get flash message from the key
     *
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public function flash(string $key, ?string $default = null): ?string
    {
        /** @var array<string,string[]> */
        $messages = $this->getFlash()->get($key);

        $lastKey = array_key_last($messages);

        return !is_null($lastKey)
            ? $messages[$lastKey]
            : $default;
    }

    /**
     * Is there a flash message for the key?
     *
     * @param string $key
     * @return boolean
     */
    public function flashHas(string $key): bool
    {
        return $this->getFlash()->has($key);
    }
}
