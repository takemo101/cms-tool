<?php

namespace Takemo101\CmsTool\Infra\Listener;

use CmsTool\Session\Flash\FlashSessionsContext;
use CmsTool\Session\SessionContext;
use Takemo101\Chubby\Event\Attribute\AsEventListener;
use Takemo101\Chubby\Http\Bridge\BeforeControllerInvoke;
use Takemo101\CmsTool\Support\Session\FlashErrorMessages;
use Takemo101\CmsTool\Support\Session\FlashOldInputs;
use Takemo101\CmsTool\Support\Twig\ErrorExtension;
use Takemo101\CmsTool\Support\Twig\FlashExtension;
use Takemo101\CmsTool\Support\Twig\OldExtension;
use Takemo101\CmsTool\Support\Twig\SessionExtension;
use Twig\Environment;

#[AsEventListener(BeforeControllerInvoke::class)]
class TwigExtensionSetupListener
{
    /**
     * constructor
     *
     * @param Environment $twig
     */
    public function __construct(
        private Environment $twig,
    ) {
        //
    }

    /**
     * @param BeforeControllerInvoke $event
     * @return void
     */
    public function __invoke(
        BeforeControllerInvoke $event
    ): void {
        $request = $event->getRequest();

        if ($flashSessions = FlashSessionsContext::fromRequest($request)
            ?->getFlashSessions()
        ) {
            $this->twig->getExtension(ErrorExtension::class)
                ->setErrors(
                    $flashSessions->get(FlashErrorMessages::class),
                );

            $this->twig->getExtension(OldExtension::class)
                ->setOldInputs(
                    $flashSessions->get(FlashOldInputs::class),
                );
        }

        if ($session = SessionContext::fromRequest($request)
            ?->getSession()
        ) {
            $this->twig->getExtension(SessionExtension::class)
                ->setSession($session);

            $this->twig->getExtension(FlashExtension::class)
                ->setFlash($session->getFlash());
        }
    }
}
