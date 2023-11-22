<?php

use CmsTool\Support\Encrypt\Encrypter;
use CmsTool\Support\Encrypt\Exception\EncryptException;
use CmsTool\Support\Encrypt\Exception\DecryptException;
use Takemo101\Chubby\Support\ServiceLocator;

if (!function_exists('encrypt')) {
    /**
     * Encrypt the given value
     *
     * @param string $value
     * @return string
     * @throws EncryptException
     */
    function encrypt(
        string $value,
    ): string {
        /** @var Encrypter */
        $encrypter = ServiceLocator::container()->get(Encrypter::class);

        return $encrypter->encrypt($value);
    }
}

if (!function_exists('decrypt')) {
    /**
     * Decrypt the given value
     *
     * @param string $value
     * @return string
     * @throws DecryptException
     */
    function decrypt(
        string $value,
    ): string {
        /** @var Encrypter */
        $encrypter = ServiceLocator::container()->get(Encrypter::class);

        return $encrypter->decrypt($value);
    }
}
