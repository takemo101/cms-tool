<?php

use CmsTool\Support\Encrypt\Encrypter;
use CmsTool\Support\Encrypt\EncryptException;
use CmsTool\Support\Encrypt\Exception\DecryptException;

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
        $encrypter = container()->get(Encrypter::class);

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
        $encrypter = container()->get(Encrypter::class);

        return $encrypter->decrypt($value);
    }
}
