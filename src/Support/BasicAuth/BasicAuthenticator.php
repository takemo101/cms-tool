<?php

namespace Takemo101\CmsTool\Support\BasicAuth;

use CmsTool\Support\Hash\Hasher;

class BasicAuthenticator
{
    /**
     * constructor
     *
     * @param
     */
    public function __construct(
        private readonly BasicAuthUsers $users,
        private readonly Hasher $hasher,
    ) {
        //
    }

    /**
     * Check if the username and password match.
     *
     * @param string $username
     * @param string $plainPassword
     * @return boolean
     */
    public function check(string $username, string $plainPassword): bool
    {
        $user = $this->users->get($username);

        if ($user === false) {
            return false;
        }

        $hashedPassword = $user->password;

        return $this->hasher->verify($plainPassword, $hashedPassword);
    }
}
