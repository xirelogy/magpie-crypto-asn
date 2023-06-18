<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension issuer alternative name
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.8
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.7
 */
#[FactoryTypeClass(IssuerAlternativeName::OID, X509ExtensionOid::class)]
class IssuerAlternativeName extends AlternativeName
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'issuer-alternative-name';
    /**
     * Current OID
     */
    public const OID = '2.5.29.18';
}