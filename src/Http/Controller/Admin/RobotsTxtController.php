<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Http\Request\Admin\SaveRobotsTxtRequest;
use Takemo101\CmsTool\Infra\Storage\Repository\RobotsTxtRepository;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;

class RobotsTxtController
{
    /**
     * @param RobotsTxtRepository $repository
     * @return View
     */
    public function edit(
        RobotsTxtRepository $repository,
    ): View {
        $content = $repository->get();

        return view(
            'cms-tool::robots.edit',
            compact('content'),
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param RobotsTxtRepository $repository
     * @return ToastRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        RobotsTxtRepository $repository,
    ): ToastRenderer {
        $form = $validator->throwIfFailed(
            $request,
            SaveRobotsTxtRequest::class,
        );

        $repository->save($form->content);

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Update,
        );
    }
}
