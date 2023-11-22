<?php

namespace CmsTool\Support\Encrypt;

enum EncryptCipher: string
{
    case AES_256_CBC = 'aes-256-cbc';
    case AES_128_CBC = 'aes-128-cbc';

    /**
     * Get the key length of the encryption key.
     *
     * @return int<1,max>
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
        $length = $this->getKeyLength();

        return random_bytes($length);
    }

    /**
     * Acquire an encryption method from an ambiguous value
     *
     * @param string|self $value
     * @return static
     */
    public static function fromAmbiguousValue(string|self $value): static
    {
        return is_string($value)
            ? static::from(strtolower($value))
            : $value;
    }
}
