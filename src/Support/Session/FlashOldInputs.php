<?php

namespace Takemo101\CmsTool\Support\Session;

use CmsTool\Session\Flash\AbstractFlashSession;
use Illuminate\Support\Arr;


class FlashOldInputs extends AbstractFlashSession
{
    /**
     * @var string
     */
    public const SessionKey = '_old_inputs';

    /**
     * @var array<string,string[]>
     */
    protected function getInputs(): array
    {
        /** @var array<string,mixed> */
        $inputs = $this->fetch();

        return $inputs;
    }

    /**
     * Get flash input data from the key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $inputs = $this->getInputs();

        return Arr::get($inputs, $key, $default);
    }

    /**
     * Is there a flash data for the key?
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        $inputs = $this->getInputs();

        return Arr::has($inputs, $key);
    }

    /**
     * Obtain all flash data
     *
     * @return array<string,mixed>
     */
    public function all(): array
    {
        return $this->getInputs();
    }
}
