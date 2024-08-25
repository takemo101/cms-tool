<?php

namespace Takemo101\CmsTool\Support\BasicAuth;

use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObject;
use Takemo101\CmsTool\Support\ArrayObject\ImmutableArrayObjectable;

class BasicAuthUsers
{
    /**
     * @var array<string,string>
     */
    private array $users = [];

    /**
     * constructor
     *
     * @param array<string,string> $users [username => password]
     */
    public function __construct(
        array $users,
    ) {
        foreach ($users as $username => $password) {
            $this->set($username, $password);
        }
    }

    /**
     * Set a user.
     *
     * @param string $username
     * @param string $hashedPassword
     * @return void
     */
    public function set(string $username, string $hashedPassword): void
    {
        assert(
            empty($username) === false,
            'username is empty'
        );

        assert(
            empty($hashedPassword) === false,
            'password is empty'
        );

        $this->users[$username] = $hashedPassword;
    }

    /**
     * Get a user.
     * If the user does not exist, it returns false.
     *
     * @param string $username
     * @return ImmutableArrayObjectable&object{
     *   username: string,
     *   password: string,
     * }
     */
    public function get(string $username): object|false
    {
        $users = $this->users;

        if ($hashedPassword = $users[$username] ?? false) {
            return ImmutableArrayObject::of([
                'username' => $username,
                'password' => $hashedPassword
            ]);
        }

        return false;
    }
}
