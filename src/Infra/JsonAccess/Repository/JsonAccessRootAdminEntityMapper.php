<?php

namespace Takemo101\CmsTool\Infra\JsonAccess\Repository;

use Takemo101\CmsTool\Domain\Admin\RootAdmin;
use Takemo101\CmsTool\Domain\Shared\EmailAddress;
use Takemo101\CmsTool\Domain\Shared\HashedPassword;

/**
 * @phpstan-type RootAdminData = array{
 *   name: string,
 *   email: string,
 *   password: string,
 * }
 */
class JsonAccessRootAdminEntityMapper
{
    /**
     * @param RootAdmin $root
     * @return array<string,mixed>
     */
    public function toArray(RootAdmin $root): array
    {
        return [
            'name' => $root->name,
            'email' => (string) $root->email,
            'password' => (string) $root->password,
        ];
    }

    /**
     * @param RootAdminData $data
     * @return RootAdmin
     */
    public function toEntity(array $data): RootAdmin
    {
        return new RootAdmin(
            name: $data['name'],
            email: new EmailAddress($data['email']),
            password: new HashedPassword($data['password']),
        );
    }
}
