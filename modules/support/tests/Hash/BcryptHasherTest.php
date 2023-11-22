<?php

use CmsTool\Support\Hash\BcryptHasher;
use CmsTool\Support\Hash\Exception\HashingException;


describe(
    'BcryptHasher',
    function () {

        test(
            'should generate a hash correctly',
            function () {
                $hasher = new BcryptHasher();

                $plainText = 'password123';
                $hashedText = $hasher->hash($plainText);

                expect($hashedText)->not->toBe($plainText);
                expect(password_verify($plainText, $hashedText))->toBeTrue();
            }
        );

        test(
            'should verify a correct hash',
            function () {
                $hasher = new BcryptHasher();

                $plainText = 'password123';

                $hashedText = password_hash($plainText, PASSWORD_BCRYPT);

                expect($hasher->verify($plainText, $hashedText))->toBeTrue();
            }
        );

        test(
            'should throw an exception when hashing fails',
            function () {
                $hasher = new BcryptHasher(-1);

                expect(fn () => $hasher->hash('password123'))
                    ->toThrow(HashingException::class);
            }
        );
    }
)->group('bcrypt-hasher', 'hash');
