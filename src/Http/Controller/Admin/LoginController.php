<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Http\Request\Admin\LoginRequest;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;
use Takemo101\CmsTool\UseCase\Admin\Auth\AdminSession;
use Takemo101\CmsTool\UseCase\Admin\Handler\Login\LoginAdminCommand;
use Takemo101\CmsTool\UseCase\Admin\Handler\Login\LoginAdminHandler;
use Takemo101\CmsTool\UseCase\Shared\Exception\LoginFailedException;

class LoginController
{
    /**
     * Login form page
     *
     * @return View
     */
    public function form(): View
    {
        return view('cms-tool::auth.login');
    }

    /**
     * Login
     *
     * @param ServerRequestInterface $request
     * @param RequestValidator $validator
     * @param AdminSession $session
     * @param LoginAdminHandler $handler
     * @return ToastRenderer
     */
    public function login(
        ServerRequestInterface $request,
        RequestValidator $validator,
        AdminSession $session,
        LoginAdminHandler $handler,
    ): ToastRenderer {
        $form = $validator->throwIfFailed(
            $request,
            LoginRequest::class,
        );

        try {
            $handler->handle(
                new LoginAdminCommand(
                    $form->email,
                    $form->password,
                ),
                $session,
            );
        } catch (LoginFailedException $e) {
            throw HttpValidationErrorException::fromMessages(
                [
                    'email' => [
                        'メールアドレス or パスワードが間違っています。',
                    ],
                ],
                $request,
            );
        }

        return toast(
            response: redirect()->route('admin.dashboard'),
            style: ToastStyle::Success,
            message: 'ログインしました',
        );
    }

    /**
     * Logout
     *
     * @param AdminSession $session
     * @return ToastRenderer
     */
    public function logout(AdminSession $session): ToastRenderer
    {
        $session->logout();

        return toast(
            response: redirect()->route('admin.login'),
            style: ToastStyle::Success,
            message: 'ログアウトしました',
        );
    }
}
