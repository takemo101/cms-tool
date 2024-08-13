<?php

namespace Takemo101\CmsTool\Support\Twig;

use Takemo101\CmsTool\Support\Session\FlashOldInputs;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Takemo101\Chubby\Context\ContextRepository;

class OldExtension extends AbstractExtension
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
     * Get the value of old inputs
     *
     * @return FlashOldInputs
     */
    private function getOldInputs(): FlashOldInputs
    {
        return $this->repository->get()
            ->getTyped(FlashOldInputs::class);
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('old', $this->old(...)),
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
