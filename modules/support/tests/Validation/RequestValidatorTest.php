<?php

use CmsTool\Support\Validation\FormRequest;
use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

beforeEach(function () {
    $this->violations = Mockery::mock(ConstraintViolationListInterface::class);

    $validator = Mockery::mock(ValidatorInterface::class);
    $validator->shouldReceive('validate')->andReturn($this->violations);

    $this->validator = $validator;

    $request = Mockery::mock(ServerRequestInterface::class);
    $request->shouldReceive('getQueryParams')->andReturn([]);
    $request->shouldReceive('getParsedBody')->andReturn([]);
    $request->shouldReceive('getUploadedFiles')->andReturn([]);

    $this->request = $request;

    $this->class = TestFormRequest::class;
});

describe(
    'RequestValidator::validate',
    function () {

        it(
            'should create and validate the form request',
            function () {
                $validator = new RequestValidator($this->validator);

                $formRequest = $validator->validate($this->request, $this->class);

                expect($formRequest)->toBeInstanceOf($this->class);
                expect($formRequest->getErrors())->toBe($this->violations);
            }
        );

        it(
            'should throw an exception if the class is not a subclass of FormRequest',
            function () {
                $validator = new RequestValidator($this->validator);
                $invalidClass = 'InvalidClass';

                expect(fn () => $validator->validate($this->request, $invalidClass))
                    ->toThrow(InvalidArgumentException::class);
            }
        );
    }
)->group('request-validator', 'validation');

describe(
    'RequestValidator::throwIfFailed',
    function () {

        it(
            'should create and validate the form request and throw an exception if validation fails',
            function () {
                $validator = new RequestValidator($this->validator);
                $formRequest = Mockery::mock(FormRequest::class);
                $formRequest->shouldReceive('populate');
                $formRequest->shouldReceive('setErrors');
                $formRequest->shouldReceive('isFailed')->andReturn(true);
                $formRequest->shouldReceive('getErrors')->andReturn($this->violations);

                expect(fn () => $validator->throwIfFailed($this->request, $formRequest))
                    ->toThrow(HttpValidationErrorException::class);
            }
        );

        it(
            'should create and validate the form request and return it if validation passes',
            function () {
                $validator = new RequestValidator($this->validator);
                $formRequest = mock(FormRequest::class);
                $formRequest->shouldReceive('populate');
                $formRequest->shouldReceive('setErrors');
                $formRequest->shouldReceive('isFailed')->andReturn(false);
                $formRequest->shouldReceive('getErrors')->andReturn($this->violations);

                $actual = $validator->throwIfFailed($this->request, $formRequest);

                expect($actual)->toBe($formRequest);
            }
        );
    }
)->group('request-validator', 'validation');

class TestFormRequest extends FormRequest
{
    //
}
