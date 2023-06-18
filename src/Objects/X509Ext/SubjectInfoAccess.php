<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * Subject info access
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.2.2
 */
#[FactoryTypeClass(SubjectInfoAccess::OID, X509ExtensionOid::class)]
class SubjectInfoAccess extends InfoAccess
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'subject-info-access';
    /**
     * Current OID
     */
    public const OID = '1.3.6.1.5.5.7.1.11';
}