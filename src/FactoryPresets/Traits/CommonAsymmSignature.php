<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\Traits;

/**
 * Common asymmetric signature algorithm
 */
trait CommonAsymmSignature
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
    public function getAsymmAlgoTypeClass() : string
    {
        return static::ASYMM_TYPECLASS;
    }


    /**
     * @inheritDoc
     */
    public function getHashAlgoTypeClass() : string
    {
        return static::HASH_TYPECLASS;
    }
}