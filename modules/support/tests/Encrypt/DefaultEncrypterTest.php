<?php

use CmsTool\Support\Encrypt\DefaultEncrypter;
use CmsTool\Support\Encrypt\EncryptCipher;
use CmsTool\Support\Encrypt\Exception\DecryptException;

beforeEach(function () {

    $cipher = EncryptCipher::AES_128_CBC;

    $this->key = $cipher->generateKey();
    $this->cipher = $cipher;
    $this->encrypter = new DefaultEncrypter($this->key, $this->cipher);
});

describe(
    'Encrypter::__construct',
    function () {
        it(
            'should throw an exception if the key length is invalid',
            function () {
                $invalidKey = 'short';

                expect(fn () => new DefaultEncrypter($invalidKey, $this->cipher))
                    ->toThrow(InvalidArgumentException::class);
            }
        );
    }
)->group('encrypt');

describe(
    'Encrypter::encrypt',
    function () {

        it(
            'should encrypt the given value',
            function () {
                $value = 'secret-value';

                $actual = $this->encrypter->encrypt($value);

                expect($actual)->not->toBe($value);
            }
        );
    }
)->group('encrypt');

describe(
    'Encrypter::decrypt',
    function () {

        it(
            'should decrypt the given value',
            function () {
                $value = 'secret-value';

                $encrypted = $this->encrypter->encrypt($value);

                $actual = $this->encrypter->decrypt($encrypted);

                expect($actual)->toBe($value);
            }
        );

        it(
            'should throw an exception if the payload is invalid',
            function () {
                expect(fn () => $this->encrypter->decrypt('invalid-payload'))
                    ->toThrow(DecryptException::class);
            }
        );

        it(
            'should throw an exception if decryption fails',
            function () {
                $value = 'secret-value';

                $encrypted = $this->encrypter->encrypt($value);

                $cipher = EncryptCipher::AES_128_CBC;

                $encrypter = new DefaultEncrypter(
                    $cipher->generateKey(),
                    $cipher,
                );

                expect(fn () => $encrypter->decrypt($encrypted))
                    ->toThrow(DecryptException::class);
            }
        );
    }
)->group('encrypt');
