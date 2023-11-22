<?php

namespace CmsTool\Support\Validation;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;

class RequestValidator
{
    /**
     * constructor
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private ValidatorInterface $validator,
    ) {
        //
    }

    /**
     * Create form request
     *
     * @template T of FormRequest
     *
     * @param ServerRequestInterface $request
     * @param class-string<T>|T $classOrObject
     * @return T
     * @throws InvalidArgumentException
     */
    public function validate(
        ServerRequestInterface $request,
        string|FormRequest $classOrObject,
    ): FormRequest {

        if (!is_subclass_of($classOrObject, FormRequest::class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The class %s must be a subclass of %s',
                    is_string($classOrObject)
                        ? $classOrObject
                        : get_class($classOrObject),
                    FormRequest::class,
                ),
            );
        }

        $properties = [
            ...$request->getQueryParams(),
            ...(array)$request->getParsedBody(),
            ...$request->getUploadedFiles(),
        ];

        if (is_string($classOrObject)) {
            /** @var T */
            $formRequest = new $classOrObject($properties);
        } else {
            $classOrObject->populate($properties);

            $formRequest = $classOrObject;
        }

        $formRequest->setErrors(
            $this->validator->validate($formRequest),
        );

        return $formRequest;
    }

    /**
     * Create FormRequest from the request and perform validation
     *
     * @template T of FormRequest
     *
     * @param ServerRequestInterface $request
     * @param class-string<T>|T $classOrObject
     * @return T
     * @throws InvalidArgumentException|HttpValidationErrorException
     */
    public function throwIfFailed(
        ServerRequestInterface $request,
        string|FormRequest $classOrObject,
    ): FormRequest {
        $formRequest = $this->validate(
            $request,
            $classOrObject,
        );

        if ($formRequest->isFailed()) {
            throw new HttpValidationErrorException(
                errors: $formRequest->getErrors(),
                request: $request,
            );
        }

        return $formRequest;
    }
}
