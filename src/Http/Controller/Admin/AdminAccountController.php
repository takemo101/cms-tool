<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Http\Request\Admin\ChangeAdminAccountRequest;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Admin\Handler\Update\ChangeAdminAccountCommand;
use Takemo101\CmsTool\UseCase\Admin\Handler\Update\ChangeAdminAccountHandler;
use Takemo101\CmsTool\UseCase\Admin\QueryService\AdminAccountQueryService;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;

class AdminAccountController
{
    /**
     * My account page
     *
     * @return View
     */
    public function edit(
        ServerRequestInterface $request,
        AdminAccountQueryService $queryService,
        AdminSession $session,
    ): View {
        try {
            $admin = $queryService->getById(
                $session->getId()->value(),
            );
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException(
                $request,
                $e->getMessage(),
                $e,
            );
        }

        return view('cms-tool::account.edit', compact('admin'));
    }

    /**
     * Change account
     *
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @return ToastRenderer
     */
    public function update(
        ServerRequestInterface $request,
        RequestValidator $validator,
        AdminSession $session,
        ChangeAdminAccountHandler $handler,
    ): ToastRenderer {
        $form = $validator->throwIfFailed(
            $request,
            ChangeAdminAccountRequest::class,
        );

        try {
            $handler->handle(
                new ChangeAdminAccountCommand(
                    name: $form->name,
                    email: $form->email,
                    password: $form->password ?: null,
                ),
                $session,
            );
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException(
                $request,
                $e->getMessage(),
                $e,
            );
        }

        return toast(
            response: redirect()->back(),
            style: ToastStyle::Update,
        );
    }
}
