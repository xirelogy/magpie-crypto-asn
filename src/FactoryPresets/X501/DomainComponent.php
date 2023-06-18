<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\X501;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeOid;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeShortName;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonX501AttributeType;

/**
 * X.501 domain component
 */
#[FactoryTypeClass(DomainComponent::OID, X501AttributeTypeOid::class)]
#[FactoryTypeClass(DomainComponent::SHORT_NAME, X501AttributeTypeShortName::class)]
class DomainComponent extends AttributeType
{
    use CommonX501AttributeType;

    /**
     * Current OID
     */
    public const OID = '0.9.2342.19200300.100.1.25';
    /**
     * Current short name
     */
    public const SHORT_NAME = 'DC';
    /**
     * Current long name
     */
    public const LONG_NAME = 'domainComponent';
}