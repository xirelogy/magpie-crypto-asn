<?php

namespace MagpieLib\CryptoAsn\Objects\Traits;

/**
 * Common PKCS #5 algorithm
 */
trait CommonPkcs5Algorithm
{
    /**
     * @inheritDoc
     */
    public static function getOid() : string
    {
        return static::OID;
    }
}