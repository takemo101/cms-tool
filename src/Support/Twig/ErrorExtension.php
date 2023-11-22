<?php

namespace Takemo101\CmsTool\Support\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use RuntimeException;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;

class ErrorExtension extends AbstractExtension
{
    /**
     * constructor
     *
     * @param FlashErrorMessages|null $errors
     */
    public function __construct(
        private ?FlashErrorMessages $errors = null,
    ) {
        //
    }

    /**
     * Set the value of errors
     *
     * @param FlashErrorMessages $errors
     * @return void
     */
    public function setErrors(FlashErrorMessages $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Get the value of errors
     *
     * @return FlashErrorMessages
     */
    private function getErrors(): FlashErrorMessages
    {
        $errors = $this->errors;

        $errors ?? throw new RuntimeException('errors is not set');

        return $errors;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_error', [$this, 'isError']),
            new TwigFunction('error', [$this, 'error']),
            new TwigFunction('error_first', [$this, 'errorFirst']),
            new TwigFunction('error_has', [$this, 'errorHas']),
            new TwigFunction('error_all', [$this, 'errorAll']),
        ];
    }

    /**
     * Exists flash error messages?
     *
     * @return string[]
     */
    public function isError(): bool
    {
        return $this->getErrors()->isError();
    }

    /**
     * Get error messages from the key
     *
     * @param string $key
     * @return string[]
     */
    public function error(string $key): array
    {
        return $this->getErrors()->get($key);
    }

    /**
     * Get the first flash error messages from the key
     *
     * @return array<string,string[]>
     */
    public function errorFirst(string $key, ?string $default = null): ?string
    {
        return $this->getErrors()->first($key, $default);
    }

    /**
     * Is there a error message for the key?
     *
     * @param string $key
     * @return boolean
     */
    public function errorHas(string $key): bool
    {
        return $this->getErrors()->has($key);
    }

    /**
     * Obtain all error messages
     *
     * @return array<string,string[]>
     */
    public function errorAll(): array
    {
        return $this->getErrors()->all();
    }
}
