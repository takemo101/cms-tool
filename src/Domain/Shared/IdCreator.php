<?php

namespace Takemo101\CmsTool\Domain\Shared;

use Symfony\Component\Uid\Uuid;

class IdCreator
{
    /**
     * @var Ulid|null
     */
    private static ?Ulid $ulid = null;

    /**
     * 26 characters ULID generation
     *
     * @return Ulid
     */
    public static function ulid(): Ulid
    {
        $ulid = self::$ulid ?? self::$ulid = Ulid::createCurrent();

        return $ulid;
    }

    /**
     * UUID generation
     *
     * @return Uuid
     */
    public static function uuid(): Uuid
    {
        return Uuid::v4();
    }

    /**
     * Generation of random character string ID
     *
     * @param integer $length
     * @return RandomId
     */
    public static function random(int $length = 16): RandomId
    {
        return new RandomId($length);
    }
}
