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

describe('validateInputs', function () {
    it('should create and validate a FormRequest from inputs', function () {
        // Create mock inputs
        $inputs = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $hydratedObject = new stdClass();
        $errors = new ConstraintViolationList();

        // Mock the methods of ObjectMapper
        $this->mapper->shouldReceive('hydrateObject')->andReturn($hydratedObject);

        // Mock the methods of ValidatorInterface
        $this->validator->shouldReceive('validate')->andReturn($errors);

        // Call the validateInputs method
        $formRequest = $this->requestValidator->validateInputs($inputs, $this->classOrObject);

        // Assert that the FormRequest is created and validated correctly
        expect($formRequest)->toBeInstanceOf(FormRequest::class);
        expect($formRequest->getHydratedObject())->toBe($hydratedObject);
        expect($formRequest->getErrors())->toBe($errors);
    });
})->group('RequestValidator', 'validation');

describe('validate', function () {
    it('should create and validate a FormRequest from the request', function () {
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

        // Call the validate method
        $formRequest = $this->requestValidator->validate($this->request, $this->classOrObject);

        // Assert that the FormRequest is created and validated correctly
        expect($formRequest)->toBeInstanceOf(FormRequest::class);
        expect($formRequest->getHydratedObject())->toBe($hydratedObject);
        expect($formRequest->getErrors())->toBe($errors);
    });
})->group('RequestValidator', 'validation');

describe('throwIfFailedInputs', function () {
    it('should throw an exception if validation fails', function () {
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

        // Expect an exception to be thrown
        expect(fn () => $this->requestValidator->throwIfFailedInputs(
            $inputs,
            $this->request,
            $this->classOrObject,
        ))->toThrow(HttpValidationErrorException::class);
    });

    it('should return a FormRequest if validation passes', function () {
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

        // Call the throwIfFailedInputs method
        $formRequest = $this->requestValidator->throwIfFailedInputs(
            $inputs,
            $this->request,
            $this->classOrObject,
        );

        // Assert that a FormRequest is returned
        expect($formRequest)->toBeInstanceOf(FormRequest::class);
        expect($formRequest->getHydratedObject())->toBe($hydratedObject);
        expect($formRequest->getErrors())->toBe($errors);
    });
})->group('RequestValidator', 'validation');

describe('throwIfFailed', function () {
    it('should throw an exception if validation fails', function () {
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

        // Expect an exception to be thrown
        expect(fn () => $this->requestValidator->throwIfFailed(
            $this->request,
            $this->classOrObject,
        ))->toThrow(HttpValidationErrorException::class);
    });

    it('should return a FormRequest if validation passes', function () {
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

        // Call the throwIfFailed method
        $formRequest = $this->requestValidator->throwIfFailed($this->request, $this->classOrObject);

        // Assert that a FormRequest is returned
        expect($formRequest)->toBeInstanceOf(FormRequest::class);
        expect($formRequest->getHydratedObject())->toBe($hydratedObject);
        expect($formRequest->getErrors())->toBe($errors);
    });
})->group('RequestValidator', 'validation');
