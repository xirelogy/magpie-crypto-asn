<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\X501;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeOid;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeShortName;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonX501AttributeType;

/**
 * X.501 organization name
 */
#[FactoryTypeClass(OrganizationName::OID, X501AttributeTypeOid::class)]
#[FactoryTypeClass(OrganizationName::SHORT_NAME, X501AttributeTypeShortName::class)]
class OrganizationName extends AttributeType
{
    use CommonX501AttributeType;

    /**
     * Current OID
     */
    public const OID = '2.5.4.10';
    /**
     * Current short name
     */
    public const SHORT_NAME = 'O';
    /**
     * Current long name
     */
    public const LONG_NAME = 'organizationName';
}