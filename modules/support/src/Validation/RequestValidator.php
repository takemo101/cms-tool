<?php

namespace CmsTool\Support\Validation;

use EventSauce\ObjectHydrator\ObjectMapper;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;
use Psr\Http\Message\UploadedFileInterface;

class RequestValidator
{
    /**
     * constructor
     *
     * @param ValidatorInterface $validator
     * @param ObjectMapper $mapper
     */
    public function __construct(
        private ValidatorInterface $validator,
        private ObjectMapper $mapper,
    ) {
        //
    }

    /**
     * Create form request
     *
     * @template T of object
     *
     * @param ServerRequestInterface $request
     * @param class-string<T>|T $classOrObject
     * @return FormRequest<T>&T
     */
    public function validate(
        ServerRequestInterface $request,
        string|object $classOrObject,
    ): FormRequest {

        $inputs = $this->extractInputs($request);

        /** @var FormRequest<T> */
        $formRequest = new FormRequest(
            $this->mapper,
            is_string($classOrObject)
                ? $this->mapper->hydrateObject(
                    $classOrObject,
                    $inputs,
                )
                : $classOrObject,
            $inputs,
        );

        $formRequest->setErrors(
            $this->validator->validate($formRequest->getHydratedObject()),
        );

        return $formRequest; // @phpstan-ignore-line
    }

    /**
     * Create FormRequest from the request and perform validation
     *
     * @template T of object
     *
     * @param ServerRequestInterface $request
     * @param class-string<T>|T $classOrObject
     * @return FormRequest<T>&T
     * @throws InvalidArgumentException|HttpValidationErrorException
     */
    public function throwIfFailed(
        ServerRequestInterface $request,
        string|object $classOrObject,
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

    /**
     * Extract inputs from the request
     *
     * @param ServerRequestInterface $request
     * @return array<string,mixed>
     */
    private function extractInputs(ServerRequestInterface $request): array
    {
        /** @var array<string,mixed> */
        $params = [
            ...$request->getQueryParams(),
            ...(array)$request->getParsedBody(),
            ...$request->getUploadedFiles(),
        ];

        return $params;
    }
}
