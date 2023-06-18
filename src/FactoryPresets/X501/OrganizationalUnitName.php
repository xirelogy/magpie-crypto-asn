<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\X501;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeOid;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeShortName;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonX501AttributeType;

/**
 * X.501 organizational unit name
 */
#[FactoryTypeClass(OrganizationalUnitName::OID, X501AttributeTypeOid::class)]
#[FactoryTypeClass(OrganizationalUnitName::SHORT_NAME, X501AttributeTypeShortName::class)]
class OrganizationalUnitName extends AttributeType
{
    use CommonX501AttributeType;

    /**
     * Current OID
     */
    public const OID = '2.5.4.11';
    /**
     * Current short name
     */
    public const SHORT_NAME = 'OU';
    /**
     * Current long name
     */
    public const LONG_NAME = 'organizationalUnitName';
}