<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;
use Takemo101\CmsTool\Http\Request\Admin\SaveRobotsTxtRequest;
use Takemo101\CmsTool\Infra\Storage\Repository\RobotsTxtRepository;

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
     * @return RedirectBackRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        RobotsTxtRepository $repository,
    ): RedirectBackRenderer {
        $form = $validator->throwIfFailed(
            $request,
            SaveRobotsTxtRequest::class,
        );

        $repository->save($form->content);

        return redirect()->back();
    }
}
