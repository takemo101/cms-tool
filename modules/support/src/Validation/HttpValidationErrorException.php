<?php

namespace CmsTool\Support\Validation;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class HttpValidationErrorException extends HttpSpecializedException
{
    /**
     * @var integer
     */
    protected $code = 422;

    /**
     * @var string
     */
    protected $message = 'The given data was invalid';

    /**
     * @var string
     */
    protected string $title = '422 Unprocessable Entity';

    /**
     * @var string
     */
    protected string $description = 'The given data was invalid. This can happen when a request body or query parameters contain invalid data.';

    /**
     * constructor
     *
     * @param ConstraintViolationListInterface $errors
     * @param ServerRequestInterface $request
     * @param string|null $message
     * @param Throwable|null $previous
     */
    public function __construct(
        private ConstraintViolationListInterface $errors,
        ServerRequestInterface $request,
        ?string $message = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($request, $message, $previous);
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
     * @param ServerRequestInterface $request
     * @return self
     */
    public static function fromMessages(
        array $messages,
        ServerRequestInterface $request,
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
            errors: new ConstraintViolationList($errors),
            request: $request,
        );
    }
}
