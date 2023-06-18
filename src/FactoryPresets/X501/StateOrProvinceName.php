<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\X501;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeOid;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeShortName;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonX501AttributeType;

/**
 * X.501 state or province name
 */
#[FactoryTypeClass(StateOrProvinceName::OID, X501AttributeTypeOid::class)]
#[FactoryTypeClass(StateOrProvinceName::SHORT_NAME, X501AttributeTypeShortName::class)]
class StateOrProvinceName extends AttributeType
{
    use CommonX501AttributeType;

    /**
     * Current OID
     */
    public const OID = '2.5.4.8';
    /**
     * Current short name
     */
    public const SHORT_NAME = 'ST';
    /**
     * Current long name
     */
    public const LONG_NAME = 'stateOrProvinceName';
}