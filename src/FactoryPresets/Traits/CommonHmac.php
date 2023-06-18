<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\Traits;

/**
 * Common HMAC algorithm
 */
trait CommonHmac
{
    /**
     * @inheritDoc
     */
    public static function getOid() : string
    {
        return static::OID;
    }


    /**
     * @inheritDoc
     */
    public function getHashAlgoTypeClass() : string
    {
        return static::HASH_TYPECLASS;
    }
}