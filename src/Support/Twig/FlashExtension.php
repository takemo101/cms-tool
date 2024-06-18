<?php

namespace Takemo101\CmsTool\Support\Twig;

use Odan\Session\FlashInterface as Flash;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Takemo101\Chubby\Context\ContextRepository;

class FlashExtension extends AbstractExtension
{
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
     * Get the value of flash
     *
     * @return Flash
     */
    private function getFlash(): Flash
    {
        return $this->repository->get()
            ->getTyped(Flash::class);
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', $this->flash(...)),
            new TwigFunction('flash_has', $this->flashHas(...)),
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
