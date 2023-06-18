<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * X.509 extension subject alternative name
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.1.7
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.1.6
 */
#[FactoryTypeClass(SubjectAlternativeName::OID, X509ExtensionOid::class)]
class SubjectAlternativeName extends AlternativeName
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'subject-alternative-name';
    /**
     * Current OID
     */
    public const OID = '2.5.29.17';
}