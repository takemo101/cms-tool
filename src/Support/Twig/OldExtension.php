<?php

namespace Takemo101\CmsTool\Support\Twig;

use Takemo101\CmsTool\Support\Session\FlashOldInputs;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use RuntimeException;

class OldExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param FlashOldInputs|null $old
     */
    public function __construct(
        private ?FlashOldInputs $old = null,
    ) {
        //
    }

    /**
     * Set the value of old inputs
     *
     * @param FlashOldInputs $old
     * @return void
     */
    public function setOldInputs(FlashOldInputs $old): void
    {
        $this->old = $old;
    }

    /**
     * Get the value of old inputs
     *
     * @return FlashOldInputs
     */
    private function getOldInputs(): FlashOldInputs
    {
        $old = $this->old;

        $old ?? throw new RuntimeException('old is not set');

        return $old;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('old', [$this, 'old']),
        ];
    }

    /**
     * Get flash data from the key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function old(string $key, mixed $default = null): mixed
    {
        return $this->getOldInputs()->get($key, $default);
    }
}
