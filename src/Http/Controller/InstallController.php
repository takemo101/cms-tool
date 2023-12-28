<?php

namespace Takemo101\CmsTool\Http\Controller;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\RouteRedirectRenderer;
use Takemo101\CmsTool\Domain\Install\InstallationNotPossibleException;
use Takemo101\CmsTool\Domain\MicroCms\MicroCmsApiAccessException;
use Takemo101\CmsTool\Http\Request\Install\SaveMicroCmsApiRequest;
use Takemo101\CmsTool\Http\Request\Install\SaveBasicSettingRequest;
use Takemo101\CmsTool\Http\ViewModel\InstallPage;
use Takemo101\CmsTool\UseCase\BasicSetting\Handler\RootAdminForSaveBasicSettingCommand;
use Takemo101\CmsTool\UseCase\BasicSetting\Handler\SaveBasicSettingCommand;
use Takemo101\CmsTool\UseCase\BasicSetting\Handler\SaveBasicSettingHandler;
use Takemo101\CmsTool\UseCase\Install\Handler\InstallHandler;
use Takemo101\CmsTool\UseCase\Install\QueryService\InstallSettingQueryService;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiCommand;
use Takemo101\CmsTool\UseCase\MicroCms\Handler\SaveMicroCmsApiHandler;

class InstallController
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

    /**
     * microCMS Api page
     *
     * @return View
     */
    public function api(): View
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.api',
            new InstallPage($data),
        );
    }

    /**
     * Save microCMS Api
     *
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param SaveMicroCmsApiHandler $handler
     * @return RouteRedirectRenderer
     */
    public function saveApi(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveMicroCmsApiHandler $handler,
    ): RouteRedirectRenderer {
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

        return redirect()->route('install.basic');
    }

    /**
     * Site basic setting page
     *
     * @return View
     */
    public function basicSetting(): View
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.basic',
            new InstallPage($data),
        );
    }

    /**
     * Save site meta
     *
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param SaveBasicSettingHandler $handler
     * @return RouteRedirectRenderer
     */
    public function saveBasicSetting(
        ServerRequestInterface $request,
        RequestValidator $validator,
        SaveBasicSettingHandler $handler
    ): RouteRedirectRenderer {
        $form = $validator->throwIfFailed(
            $request,
            SaveBasicSettingRequest::class,
        );

        $handler->handle(
            new SaveBasicSettingCommand(
                siteName: $form->siteName,
                root: new RootAdminForSaveBasicSettingCommand(
                    name: $form->root->name,
                    email: $form->root->email,
                    password: $form->root->password,
                ),
            ),
        );

        return redirect()->route('install.confirm');
    }

    /**
     * Confirm page
     *
     * @return View
     */
    public function confirm(): View
    {
        $data = $this->queryService->get();

        return view(
            'cms-tool::install.confirm',
            new InstallPage($data),
        );
    }

    /**
     * Installed
     *
     * @param ServerRequestInterface $request
     * @param InstallHandler $handler
     * @return RouteRedirectRenderer
     */
    public function install(
        ServerRequestInterface $request,
        InstallHandler $handler,
    ): RouteRedirectRenderer {
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

        return redirect()->route('admin.login');
    }
}
