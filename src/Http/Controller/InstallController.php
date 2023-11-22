<?php

namespace Takemo101\CmsTool\Http\Controller;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\RedirectRenderer;
use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessException;
use Takemo101\CmsTool\Http\Request\Install\SaveAccountRequest;
use Takemo101\CmsTool\Http\Request\Install\SaveApiRequest;
use Takemo101\CmsTool\Http\Request\Install\SaveMetaRequest;
use Takemo101\CmsTool\Http\ViewModel\InstallPage;
use Takemo101\CmsTool\UseCase\Admin\Handler\SaveRootAdminCommand;
use Takemo101\CmsTool\UseCase\Admin\Handler\SaveRootAdminHandler;
use Takemo101\CmsTool\UseCase\Install\Handler\InstalledHandler;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiCommand;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiHandler;
use Takemo101\CmsTool\UseCase\Shared\QueryService\InstallSettingQueryService;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\SaveSiteMetaCommand;
use Takemo101\CmsTool\UseCase\SiteMeta\Handler\SaveSiteMetaHandler;

readonly class InstallController
{
    /**
     * constructor
     *
     * @param InstallSettingQueryService $queryService
     */
    public function __construct(
        private InstallSettingQueryService $queryService
    ) {
        //
    }

    public function apiPage()
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.api',
            new InstallPage($data),
        );
    }

    public function saveApi(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveMicroCmsApiHandler $handler,
    ) {
        $form = $validator->throwIfFailed(
            $request,
            SaveApiRequest::class,
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
                    'api' => [
                        'The access key or service ID is incorrect',
                    ],
                ],
                request: $request,
            );
        }

        return new RedirectRenderer(route('install.meta'));
    }

    public function metaPage()
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.meta',
            new InstallPage($data),
        );
    }

    public function saveMeta(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveSiteMetaHandler $handler
    ) {
        $form = $validator->throwIfFailed(
            $request,
            SaveMetaRequest::class,
        );

        $handler->handle(
            new SaveSiteMetaCommand(
                name: $form->name,
                title: $form->title,
                description: $form->description,
            ),
        );

        return new RedirectRenderer(route('install.account'));
    }

    public function accountPage()
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.account',
            new InstallPage($data),
        );
    }

    public function saveAccount(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveRootAdminHandler $handler,
    ) {
        $form = $validator->throwIfFailed(
            $request,
            SaveAccountRequest::class,
        );

        $handler->handle(
            new SaveRootAdminCommand(
                name: $form->name,
                password: $form->password,
            ),
        );

        return new RedirectRenderer(route('install.confirm'));
    }

    public function confirmPage()
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.confirm',
            new InstallPage($data),
        );
    }

    public function installed(
        ServerRequestInterface $request,
        InstalledHandler $handler,
    ) {
        try {
            $handler->handle();
        } catch (InstallationNotPossibleException $e) {
            throw HttpValidationErrorException::fromMessages(
                messages: [
                    'installed' => [
                        'There are unentered data items',
                    ],
                ],
                request: $request,
            );
        }

        return new RedirectRenderer(route('home'));
    }
}
