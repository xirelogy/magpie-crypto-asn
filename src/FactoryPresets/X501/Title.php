<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\X501;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeOid;
use MagpieLib\CryptoAsn\Factories\X501AttributeTypeShortName;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonX501AttributeType;

/**
 * X.501 title
 */
#[FactoryTypeClass(Title::OID, X501AttributeTypeOid::class)]
#[FactoryTypeClass(Title::SHORT_NAME, X501AttributeTypeShortName::class)]
class Title extends AttributeType
{
    use CommonX501AttributeType;

    /**
     * Current OID
     */
    public const OID = '2.5.4.12';
    /**
     * Current short name
     */
    public const SHORT_NAME = 'T';
    /**
     * Current long name
     */
    public const LONG_NAME = 'title';
}