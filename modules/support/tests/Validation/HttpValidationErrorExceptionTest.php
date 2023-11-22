<?php

use CmsTool\Support\Validation\HttpValidationErrorException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

describe(
    'HttpValidationErrorException',
    function () {
        it('should return the correct error messages', function () {
            // Create a mock ServerRequestInterface
            $request = Mockery::mock(ServerRequestInterface::class);

            // Create a list of constraint violations
            $violations = new ConstraintViolationList([
                new ConstraintViolation('Error 1', 'Error 1', [], null, 'property1', null),
                new ConstraintViolation('Error 2', 'Error 2', [], null, 'property2', null),
                new ConstraintViolation('Error 3', 'Error 3', [], null, 'property1', null),
            ]);

            // Create an instance of HttpValidationErrorException
            $exception = new HttpValidationErrorException($violations, $request);

            // Get the error messages
            $errorMessages = $exception->getErrorMessages();

            // Assert that the error messages are correct
            expect($errorMessages)->toBe([
                'property1' => ['Error 1', 'Error 3'],
                'property2' => ['Error 2'],
            ]);
        });

        it('should create a new HttpValidationErrorException from messages', function () {
            // Create a mock ServerRequestInterface
            $request = Mockery::mock(ServerRequestInterface::class);

            // Create an array of error messages
            $messages = [
                'property1' => ['Error 1', 'Error 2'],
                'property2' => ['Error 3'],
            ];

            // Create an instance of HttpValidationErrorException using the fromMessages method
            $exception = HttpValidationErrorException::fromMessages($messages, $request);

            // Get the error messages
            $errorMessages = $exception->getErrorMessages();

            // Assert that the error messages are correct
            expect($errorMessages)->toBe($messages);
        });
    }
)->group('http-validation-error-exception', 'validation');
