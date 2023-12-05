<?php

use CmsTool\Support\Validation\RequestValidator;
use CmsTool\Support\Validation\FormRequest;
use CmsTool\Support\Validation\HttpValidationErrorException;
use EventSauce\ObjectHydrator\ObjectMapper;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Mockery as m;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

beforeEach(function () {
    // Create mock objects
    $this->validator = m::mock(ValidatorInterface::class);
    $this->mapper = m::mock(ObjectMapper::class);
    $this->request = m::mock(ServerRequestInterface::class);
    $this->classOrObject = 'TestClass';

    // Create an instance of RequestValidator
    $this->requestValidator = new RequestValidator($this->validator, $this->mapper);
});

describe(
    'RequestValidator::validate',
    function () {
        it(
            'should create and validate a FormRequest',
            function () {
                // Create mock inputs
                $inputs = [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ];

                // Mock the methods of ServerRequestInterface
                $this->request->shouldReceive('getQueryParams')->andReturn([]);
                $this->request->shouldReceive('getParsedBody')->andReturn([]);
                $this->request->shouldReceive('getUploadedFiles')->andReturn([]);
                $this->request->shouldReceive('getQueryParams')->andReturn($inputs);

                $hydratedObject = new stdClass();
                $errors = new ConstraintViolationList();

                // Mock the methods of ObjectMapper
                $this->mapper->shouldReceive('hydrateObject')->andReturn($hydratedObject);

                // Mock the methods of ValidatorInterface
                $this->validator->shouldReceive('validate')->andReturn($errors);

                $requestValidator = new RequestValidator($this->validator, $this->mapper);

                // Call the validate method
                $formRequest = $requestValidator->validate($this->request, $this->classOrObject);

                // Assert that the FormRequest is created and validated correctly
                expect($formRequest)->toBeInstanceOf(FormRequest::class);
                expect($formRequest->getHydratedObject())->toBe($hydratedObject);
                expect($formRequest->getErrors())->toBe($errors);
            }
        );
    }
)->group('request-validator', 'validation');

describe(
    'RequestValidator::throwIfFailed',
    function () {

        it(
            'should throw an exception if validation fails',
            function () {
                // Create mock inputs
                $inputs = [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ];

                // Mock the methods of ServerRequestInterface
                $this->request->shouldReceive('getQueryParams')->andReturn([]);
                $this->request->shouldReceive('getParsedBody')->andReturn([]);
                $this->request->shouldReceive('getUploadedFiles')->andReturn([]);
                $this->request->shouldReceive('getQueryParams')->andReturn($inputs);

                $hydratedObject = new stdClass();
                $errors = new ConstraintViolationList([new ConstraintViolation('error', 'error', [], null, 'key1', null, null, null)]);

                // Mock the methods of ObjectMapper
                $this->mapper->shouldReceive('hydrateObject')->andReturn($hydratedObject);

                // Mock the methods of ValidatorInterface
                $this->validator->shouldReceive('validate')->andReturn($errors);

                $requestValidator = new RequestValidator($this->validator, $this->mapper);

                // Call the throwIfFailed method
                // Expect an exception to be thrown
                expect(fn () => $requestValidator->throwIfFailed(
                    $this->request,
                    $this->classOrObject,
                ))->toThrow(HttpValidationErrorException::class);
            }
        );

        it(
            'should return a FormRequest if validation passes',
            function () {

                // Create mock inputs
                $inputs = [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ];

                // Mock the methods of ServerRequestInterface
                $this->request->shouldReceive('getQueryParams')->andReturn([]);
                $this->request->shouldReceive('getParsedBody')->andReturn([]);
                $this->request->shouldReceive('getUploadedFiles')->andReturn([]);
                $this->request->shouldReceive('getQueryParams')->andReturn($inputs);

                $hydratedObject = new stdClass();
                $errors = new ConstraintViolationList();

                // Mock the methods of ObjectMapper
                $this->mapper->shouldReceive('hydrateObject')->andReturn($hydratedObject);

                // Mock the methods of ValidatorInterface
                $this->validator->shouldReceive('validate')->andReturn($errors);

                $requestValidator = new RequestValidator($this->validator, $this->mapper);

                // Call the throwIfFailed method
                $formRequest = $requestValidator->throwIfFailed($this->request, $this->classOrObject);

                // Assert that a FormRequest is returned
                expect($formRequest)->toBeInstanceOf(FormRequest::class);
                expect($formRequest->getHydratedObject())->toBe($hydratedObject);
                expect($formRequest->getErrors())->toBe($errors);
            }
        );
    }
)->group('request-validator', 'validation');
