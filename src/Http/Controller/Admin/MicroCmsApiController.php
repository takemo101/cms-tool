<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessException;
use Takemo101\CmsTool\Http\Request\Install\SaveMicroCmsApiRequest;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiCommand;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiHandler;

class MicroCmsApiController
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param SaveMicroCmsApiHandler $handler
     * @return ToastRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveMicroCmsApiHandler $handler,
    ): ToastRenderer {
        $form = $validator->throwIfFailed(
            $request,
            SaveMicroCmsApiRequest::class,
        );

        try {
            $handler->handle(
                new SaveMicroCmsApiCommand(
                    key: $form->key,
                    serviceId: $form->serviceId,
                ),
            );
        } catch (MicroCmsApiAccessException $e) {
            throw HttpValidationErrorException::fromMessages(
                messages: [
                    'key' => [
                        'The access key or service ID is incorrect',
                    ],
                ],
                request: $request,
            );
        }

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Update,
        );
    }
}
