<?php

namespace CmsTool\Support\Encrypt;

use CmsTool\Support\Encrypt\Exception\DecryptException;
use CmsTool\Support\Encrypt\Exception\EncryptException;

interface Encrypter
{
    /**
     * Encrypt the given value
     *
     * @param string $value
     * @return string
     * @throws EncryptException
     */
    public function encrypt(string $value): string;

    /**
     * Decrypt the given value
     *
     * @param string $value
     * @return string
     * @throws DecryptException
     */
    public function decrypt(string $value): string;
}
