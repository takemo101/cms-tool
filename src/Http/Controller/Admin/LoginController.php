<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Support\Validation\RequestValidator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\Chubby\Http\Renderer\RouteRedirectRenderer;
use Takemo101\CmsTool\Http\Request\Admin\LoginRequest;
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
    public function loginPage(): View
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
     * @return RouteRedirectRenderer
     */
    public function login(
        ServerRequestInterface $request,
        RequestValidator $validator,
        AdminSession $session,
        LoginAdminHandler $handler,
    ): RouteRedirectRenderer {
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

        return redirect()->route('admin.dashboard');
    }

    /**
     * Logout
     *
     * @param AdminSession $session
     * @return RouteRedirectRenderer
     */
    public function logout(AdminSession $session): RouteRedirectRenderer
    {
        $session->logout();

        return redirect()->route('admin.login');
    }
}
