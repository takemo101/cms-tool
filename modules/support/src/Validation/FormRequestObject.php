<?php

namespace CmsTool\Support\Validation;

use EventSauce\ObjectHydrator\ObjectMapper;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Illuminate\Support\Arr;
use RuntimeException;

/**
 * @template T of object
 *
 * @implements FormRequest<T>
 *
 * @mixin T
 */
class FormRequestObject implements FormRequest
{
    /**
     * constructor
     *
     * @param ObjectMapper $_mapper
     * @param T $_object
     * @param array<string,mixed> $_inputs
     */
    public function __construct(
        private ObjectMapper $_mapper,
        private object $_object,
        private array $_inputs = [],
    ) {
        //
    }

    /**
     * Get input
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->_inputs, $key, $default);
    }

    /**
     * Get all inputs
     *
     * @return array<string,mixed>
     */
    public function inputs(): array
    {
        return $this->_inputs;
    }

    /**
     * Get the hydrated object.
     *
     * @return T
     */
    public function getHydratedObject(): object
    {
        return $this->_object;
    }

    /**
     * @var ConstraintViolationListInterface|null
     */
    private ?ConstraintViolationListInterface $_errors = null;

    /**
     * Check if the form request has passed validation.
     *
     * @return bool True if the form request has passed validation, false otherwise.
     */
    public function isPassed(): bool
    {
        return $this->isValidated() && $this->getErrors()->count() === 0;
    }

    /**
     * Check if the form request has failed validation.
     *
     * @return bool True if the form request has failed validation, false otherwise.
     */
    public function isFailed(): bool
    {
        return !$this->isPassed();
    }

    /**
     * Have you already valued?
     *
     * @return boolean
     */
    public function isValidated(): bool
    {
        return $this->_errors !== null;
    }

    /**
     * Set the error content after the validation execution
     *
     * @param ConstraintViolationListInterface $errors
     * @return void
     */
    public function setErrors(ConstraintViolationListInterface $errors): void
    {
        $this->_errors = $errors;
    }

    /**
     * Get the error content after the validation execution
     *
     * @return ConstraintViolationListInterface
     * @throws RuntimeException
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->_errors ?? throw new RuntimeException('errors is not set');
    }

    /**
     * Get _object property value
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        if (property_exists($this->_object, $name)) {
            return $this->_object->{$name};
        }

        throw new RuntimeException("property {$name} is not found");
    }

    /**
     * Call the method of the _object property
     *
     * @param string $name
     * @param mixed[] $arguments
     * @return mixed
     * @throws RuntimeException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (method_exists($this->_object, $name)) {
            return $this->_object->{$name}(...$arguments);
        }

        throw new RuntimeException("method {$name} is not found");
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        /** @var array<string,mixed> */
        $serialized = $this->_mapper->serializeObject($this->_object);

        return $serialized;
    }
}
