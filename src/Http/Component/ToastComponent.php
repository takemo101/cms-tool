<?php

namespace Takemo101\CmsTool\Http\Component;

use CmsTool\View\HtmlObject;
use CmsTool\View\View;
use Takemo101\Chubby\Context\ContextRepository;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;
use Takemo101\CmsTool\Support\Toast\FlashToast;
use Takemo101\CmsTool\Support\Toast\ToastSession;

class ToastComponent
{
    /**
     * constructor
     *
     * @param ContextRepository $repository
     */
    public function __construct(
        private readonly ContextRepository $repository,
    ) {
        //
    }

    /**
     * Render toast
     *
     * @return View|null
     */
    public function __invoke(): ?View
    {
        $errors = $this->repository->get()
            ->getTyped(FlashErrorMessages::class);

        $toast = $this->repository->get()
            ->getTyped(FlashToast::class);

        $session = $toast->get();

        $session = $session
            ? $session
            : (
                $errors->isError()
                ? ToastSession::createInputError()
                : null
            );

        return $session
            ? view(
                'cms-tool::sections.toast',
                [
                    'style' => $session->style,
                    'message' => new HtmlObject(
                        preg_replace(
                            '/\n|\r|\r\n/',
                            '',
                            nl2br(e($session->message)),
                        ) ?? '',
                    )
                ]
            )
            : null;
    }
}
