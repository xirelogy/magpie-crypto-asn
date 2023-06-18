<?php

namespace MagpieLib\CryptoAsn\Objects\Traits;

/**
 * Common X.509 extension
 */
trait CommonX509Extension
{
    /**
     * @inheritDoc
     */
    public static function getTypeClass() : string
    {
        return static::TYPECLASS;
    }


    /**
     * @inheritDoc
     */
    public static function getOid() : string
    {
        return static::OID;
    }
}
