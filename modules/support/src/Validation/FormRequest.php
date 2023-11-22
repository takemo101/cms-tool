<?php

namespace CmsTool\Support\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use RuntimeException;

abstract class FormRequest extends FormRequestObject
{
    /**
     * Properties that exclude array
     *
     * @var string[]
     */
    public const ExcludeArrayProperties = ['errors'];

    /**
     * @var ConstraintViolationListInterface|null
     */
    private ?ConstraintViolationListInterface $errors = null;

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
        return $this->errors !== null;
    }

    /**
     * Set the error content after the validation execution
     *
     * @param ConstraintViolationListInterface $errors
     * @return void
     */
    public function setErrors(ConstraintViolationListInterface $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Get the error content after the validation execution
     *
     * @return ConstraintViolationListInterface
     * @throws RuntimeException
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors ?? throw new RuntimeException('errors is not set');
    }
}
