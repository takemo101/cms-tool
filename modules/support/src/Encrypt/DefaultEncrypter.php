<?php

namespace CmsTool\Support\Encrypt;

use CmsTool\Support\Encrypt\Exception\DecryptException;
use CmsTool\Support\Encrypt\Exception\EncryptException;
use InvalidArgumentException;
use RuntimeException;

/**
 * reference: https://note.com/waaaq___/n/n9b08d864c3ea
 * reference: https://github.com/laravel/framework/blob/10.x/src/Illuminate/Encryption/Encrypter.php
 */
class DefaultEncrypter implements Encrypter
{
    /**
     * constructor
     *
     * @param string $key
     * @param EncryptCipher $cipher
     * @throws InvalidArgumentException
     */
    public function __construct(
        private string $key,
        private EncryptCipher $cipher = EncryptCipher::AES_128_CBC,
    ) {
        if (mb_strlen($key, '8bit') !== $cipher->getKeyLength()) {
            throw new InvalidArgumentException(
                sprintf(
                    'The key length must be %d bytes.',
                    $cipher->getKeyLength(),
                ),
            );
        }
    }

    /**
     * Get the hashed key
     *
     * @return string
     */
    private function getHashedKey(): string
    {
        return hash('sha256', $this->key, true);
    }

    /**
     * Encrypt the given value
     *
     * @param string $value
     * @return string
     * @throws EncryptException
     */
    public function encrypt(string $value): string
    {
        $ivLength = openssl_cipher_iv_length($this->cipher->value);

        if ($ivLength === false) {
            throw new RuntimeException('Failed to get iv length.');
        }

        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt(
            $value,
            $this->cipher->value,
            $this->getHashedKey(),
            OPENSSL_RAW_DATA,
            $iv,
        );

        if ($encrypted === false) {
            throw EncryptException::encryptionFailedError();
        }

        $iv = base64_encode($iv);
        $encrypted = base64_encode($encrypted);

        $json = json_encode(compact('iv', 'encrypted'), JSON_UNESCAPED_SLASHES);

        if (!$json || json_last_error() !== JSON_ERROR_NONE) {
            throw EncryptException::encodeJsonFailedError();
        }

        return base64_encode($json);
    }

    /**
     * Decrypt the given value
     *
     * @param string $value
     * @return string
     * @throws DecryptException
     */
    public function decrypt(string $value): string
    {
        /** @var array<string,mixed> */
        $payload = json_decode(base64_decode($value), true);

        if (
            !is_array($payload)
            || json_last_error() !== JSON_ERROR_NONE
        ) {
            throw DecryptException::decodeJsonFailedError();
        }

        /** @var string */
        $iv = $payload['iv'] ?? throw DecryptException::invalidPayloadError();

        /** @var string */
        $encrypted = $payload['encrypted'] ?? throw DecryptException::invalidPayloadError();

        $iv = base64_decode($iv);

        $encrypted = base64_decode($encrypted);

        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher->value,
            $this->getHashedKey(),
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($decrypted === false) {
            throw DecryptException::decryptionFailedError();
        }

        return $decrypted;
    }
}
