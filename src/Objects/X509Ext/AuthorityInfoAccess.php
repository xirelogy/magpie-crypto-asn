<?php

namespace MagpieLib\CryptoAsn\Objects\X509Ext;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\X509ExtensionOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonX509Extension;

/**
 * Authority info access
 * @link https://www.rfc-editor.org/rfc/rfc2459#section-4.2.2.1
 * @link https://www.rfc-editor.org/rfc/rfc5280#section-4.2.2.1
 */
#[FactoryTypeClass(AuthorityInfoAccess::OID, X509ExtensionOid::class)]
class AuthorityInfoAccess extends InfoAccess
{
    use CommonX509Extension;

    /**
     * Current type class
     */
    public const TYPECLASS = 'authority-info-access';
    /**
     * Current OID
     */
    public const OID = '1.3.6.1.5.5.7.1.1';
}