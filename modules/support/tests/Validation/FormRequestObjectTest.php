<?php

use CmsTool\Support\Validation\FormRequestObject;
use EventSauce\ObjectHydrator\ObjectMapper;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Mockery as m;
use Symfony\Component\Validator\ConstraintViolation;

describe(
    'FormRequest',
    function () {
        it(
            'should return the correct input value',
            function () {
                // Create a mock ObjectMapper
                $mapper = m::mock(ObjectMapper::class);

                // Create a mock object
                $object = new stdClass();

                // Create an array of inputs
                $inputs = [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ];

                // Create an instance of FormRequest
                $formRequest = new FormRequestObject($mapper, $object, $inputs);

                // Get the input value
                $inputValue = $formRequest->input('key1');

                // Assert that the input value is correct
                expect($inputValue)->toBe('value1');
            }
        );

        it(
            'should return all inputs',
            function () {
                // Create a mock ObjectMapper
                $mapper = m::mock(ObjectMapper::class);

                // Create a mock object
                $object = new stdClass();

                // Create an array of inputs
                $inputs = [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ];

                // Create an instance of FormRequest
                $formRequest = new FormRequestObject($mapper, $object, $inputs);

                // Get all inputs
                $allInputs = $formRequest->inputs();

                // Assert that all inputs are correct
                expect($allInputs)->toBe($inputs);
            }
        );

        it(
            'should return the hydrated object',
            function () {
                // Create a mock ObjectMapper
                $mapper = m::mock(ObjectMapper::class);

                // Create a mock object
                $object = new stdClass();

                // Create an instance of FormRequest
                $formRequest = new FormRequestObject($mapper, $object);

                // Get the hydrated object
                $hydratedObject = $formRequest->getHydratedObject();

                // Assert that the hydrated object is correct
                expect($hydratedObject)->toBe($object);
            }
        );

        it(
            'should return true if the form request has passed validation',
            function () {
                // Create a mock ObjectMapper
                $mapper = m::mock(ObjectMapper::class);

                // Create a mock object
                $object = new stdClass();

                // Create an instance of FormRequest
                $formRequest = new FormRequestObject($mapper, $object);

                // Set the errors to an empty ConstraintViolationList
                $formRequest->setErrors(new ConstraintViolationList());

                // Check if the form request has passed validation
                $isPassed = $formRequest->isPassed();

                // Assert that the form request has passed validation
                expect($isPassed)->toBeTrue();
            }
        );

        it('should return true if the form request has failed validation', function () {
            // Create a mock ObjectMapper
            $mapper = m::mock(ObjectMapper::class);

            // Create a mock object
            $object = new stdClass();

            // Create an instance of FormRequest
            $formRequest = new FormRequestObject($mapper, $object);

            // Set the errors to a non-empty ConstraintViolationList
            $formRequest->setErrors(new ConstraintViolationList([
                new ConstraintViolation('Error 1', 'Error 1', [], null, 'property1', null),
            ]));

            // Check if the form request has failed validation
            $isFailed = $formRequest->isFailed();

            // Assert that the form request has failed validation
            expect($isFailed)->toBeTrue();
        });

        it('should return true if the form request has been validated', function () {
            // Create a mock ObjectMapper
            $mapper = m::mock(ObjectMapper::class);

            // Create a mock object
            $object = new stdClass();

            // Create an instance of FormRequest
            $formRequest = new FormRequestObject($mapper, $object);

            // Set the errors to a non-null value
            $formRequest->setErrors(new ConstraintViolationList());

            // Check if the form request has been validated
            $isValidated = $formRequest->isValidated();

            // Assert that the form request has been validated
            expect($isValidated)->toBeTrue();
        });

        it('should throw an exception when getting errors if not set', function () {
            // Create a mock ObjectMapper
            $mapper = m::mock(ObjectMapper::class);

            // Create a mock object
            $object = new stdClass();

            // Create an instance of FormRequest
            $formRequest = new FormRequestObject($mapper, $object);

            // Get the errors
            // Expect a RuntimeException to be thrown when getting errors
            expect(fn() => $formRequest->getErrors())
                ->toThrow(RuntimeException::class);
        });

        it('should return the serialized object as an array', function () {
            // Create a mock ObjectMapper
            $mapper = m::mock(ObjectMapper::class);
            $serializedObject = ['key1' => 'value1', 'key2' => 'value2'];

            // Mock the serializeObject method of the ObjectMapper
            $mapper->shouldReceive('serializeObject')->andReturn($serializedObject);

            // Create a mock object
            $object = new stdClass();

            // Create an instance of FormRequest
            $formRequest = new FormRequestObject($mapper, $object);

            // Convert the object to an array
            $arrayRepresentation = $formRequest->toArray();

            // Assert that the array representation is correct
            expect($arrayRepresentation)->toBe($serializedObject);
        });
    }
)->group('FormRequestObject', 'validation');
