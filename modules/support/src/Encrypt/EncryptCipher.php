<?php

namespace CmsTool\Support\Encrypt;

enum EncryptCipher: string
{
    case AES_256_CBC = 'aes-256-cbc';
    case AES_128_CBC = 'aes-128-cbc';

    /**
     * Get the key length of the encryption key.
     *
     * @return integer
     */
    public function getKeyLength(): int
    {
        return match ($this) {
            self::AES_256_CBC => 32,
            self::AES_128_CBC => 16,
        };
    }

    /**
     * Create a new encryption key.
     *
     * @return string
     */
    public function generateKey(): string
    {
        return random_bytes($this->getKeyLength());
    }

    /**
     * Acquire an encryption method from an ambiguous value
     *
     * @param string|self $value
     * @return self
     */
    public static function fromAmbiguousValue(string|self $value): self
    {
        return is_string($value)
            ? self::from(strtolower($value))
            : $value;
    }
}
