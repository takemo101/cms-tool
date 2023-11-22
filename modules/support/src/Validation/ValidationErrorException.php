<?php

namespace CmsTool\Support\Validation;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;
use Exception;

class ValidationErrorException extends Exception
{
    /**
     * constructor
     *
     * @param ConstraintViolationListInterface $errors
     * @param string|null $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(
        private ConstraintViolationListInterface $errors,
        string $message = 'The given data was invalid',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }

    /**
     * Get the value of errors
     *
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }

    /**
     * Get an error message as an array
     *
     * @return array<string,string[]>
     */
    public function getErrorMessages(): array
    {
        $messages = [];

        /** @var ConstraintViolationInterface[] */
        $errors = $this->errors;

        foreach ($errors as $violation) {
            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $messages;
    }

    /**
     * Create a new ValidationErrorException from a list of messages
     *
     * @param array<string,string[]> $messages
     * @return self
     */
    public static function fromMessages(
        array $messages,
    ): self {
        $errors = [];

        foreach ($messages as $property => $messages) {
            foreach ($messages as $message) {
                $errors[] = new ConstraintViolation(
                    $message,
                    $message,
                    [],
                    null,
                    $property,
                    null,
                    null,
                    null,
                );
            }
        }

        return new self(
            new ConstraintViolationList($errors),
        );
    }
}
