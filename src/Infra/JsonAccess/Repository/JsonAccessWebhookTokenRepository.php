<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use CmsTool\Support\Encrypt\Encrypter;
use Takemo101\CmsTool\Domain\Webhook\WebhookToken;
use Takemo101\CmsTool\Domain\Webhook\WebhookTokenRepository;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonAccessObjectCreator;
use Takemo101\CmsTool\Infra\JsonAccess\SettingJsonObjectKeys;

class JsonAccessWebhookTokenRepository implements WebhookTokenRepository
{
    /**
     * constructor
     *
     * @param SettingJsonAccessObjectCreator $creator
     * @param Encrypter $encrypter
     */
    public function __construct(
        private SettingJsonAccessObjectCreator $creator,
        private Encrypter $encrypter,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function find(): ?WebhookToken
    {
        $object = $this->creator->create();

        /** @var string|null */
        $token = $object->get(SettingJsonObjectKeys::WebhookTokenKey);

        if (!$token) {
            return null;
        }

        return new WebhookToken($this->encrypter->decrypt($token));
    }

    /**
     * Save web hook token
     *
     * @param WebhookToken $token
     * @return void
     */
    public function save(WebhookToken $token): void
    {
        $object = $this->creator->create();

        $object->set(
            SettingJsonObjectKeys::WebhookTokenKey,
            $this->encrypter->encrypt((string) $token),
        );

        $object->save();
    }
}
