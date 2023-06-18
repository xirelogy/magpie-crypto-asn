<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\Traits;

/**
 * Common X.501 attribute type
 */
trait CommonX501AttributeType
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
    public function getShortName() : string
    {
        return static::SHORT_NAME;
    }


    /**
     * @inheritDoc
     */
    public function getLongName() : string
    {
        return static::LONG_NAME;
    }
}