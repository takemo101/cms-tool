<?php

namespace CmsTool\Support\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use RuntimeException;

interface ValidatedCondition
{
    /**
     * Check if the form request has passed validation.
     *
     * @return bool True if the form request has passed validation, false otherwise.
     */
    public function isPassed(): bool;

    /**
     * Check if the form request has failed validation.
     *
     * @return bool True if the form request has failed validation, false otherwise.
     */
    public function isFailed(): bool;

    /**
     * Have you already valued?
     *
     * @return boolean
     */
    public function isValidated(): bool;

    /**
     * Set the error content after the validation execution
     *
     * @param ConstraintViolationListInterface $errors
     * @return void
     */
    public function setErrors(ConstraintViolationListInterface $errors): void;

    /**
     * Get the error content after the validation execution
     *
     * @return ConstraintViolationListInterface
     * @throws RuntimeException
     */
    public function getErrors(): ConstraintViolationListInterface;
}
