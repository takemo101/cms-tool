<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Domain\SiteMeta\SiteSeoImageTarget;
use Takemo101\CmsTool\Http\Request\Admin\ChangeSiteSeoRequest;
use Takemo101\CmsTool\Http\ViewModel\SiteSeoPage;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\ChangeSiteSeoCommand;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\ChangeSiteSeoHandler;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\CleanSiteSeoImageHandler;
use Takemo101\CmsTool\UseCase\SiteMeta\QueryService\SiteMetaQueryService;

class SiteSeoController
{
    /**
     * @param SiteMetaQueryService $queryService
     * @return View
     */
    public function edit(
        SiteMetaQueryService $queryService,
    ): View {
        $meta = $queryService->get();

        return view(
            'cms-tool::seo.edit',
            new SiteSeoPage($meta),
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param ChangeSiteSeoHandler $handler
     * @return ToastRenderer
     * @throws HttpValidationErrorException
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        ChangeSiteSeoHandler $handler,
    ): ToastRenderer {
        $form = $validator->throwIfFailed(
            $request,
            ChangeSiteSeoRequest::class,
        );

        $handler->handle(
            new ChangeSiteSeoCommand(
                title: $form->title,
                description: $form->description,
                keywords: $form->keywords,
                favicon: $form->favicon,
                icon: $form->icon,
                robots: $form->robots,
            ),
        );

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Update,
        );
    }

    /**
     * Delete images of the target items specified by SEO
     *
     * @param CleanSiteSeoImageHandler $handler
     * @param SiteMetaQueryService $queryService
     * @param string $target
     * @return View
     */
    public function deleteImage(
        CleanSiteSeoImageHandler $handler,
        SiteMetaQueryService $queryService,
        string $target,
    ): View {
        $handler->handle(
            SiteSeoImageTarget::from($target),
        );

        return view(
            'cms-tool::seo.edit',
            new SiteSeoPage($queryService->get()),
        )->addFragment("{$target}_input");
    }
}
