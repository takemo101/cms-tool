<?php

namespace Takemo101\CmsTool\Support\Toast;

use CmsTool\Session\Flash\AbstractFlashSession;

class FlashToast extends AbstractFlashSession
{
    /**
     * @var string
     */
    public const SessionKey = '_toast';

    /**
     * Get toast session data
     *
     * @var ToastSession|null
     */
    public function get(): ?ToastSession
    {
        /** @var array<string,mixed> */
        $data = $this->fetch();

        return empty($data)
            ? null
            : ToastSession::fromArray($data);
    }
}
