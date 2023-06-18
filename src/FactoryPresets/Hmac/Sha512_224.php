<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\Hmac;

use Magpie\Cryptos\Algorithms\Hashes\CommonHashTypeClass;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\HmacOid;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonHmac;

/**
 * SHA512-224
 */
#[FactoryTypeClass(Sha512_224::OID, HmacOid::class)]
class Sha512_224 extends Hmac
{
    use CommonHmac;

    /**
     * Current OID
     */
    public const OID = '1.2.840.113549.2.12';
    /**
     * Current hash algorithm
     */
    public const HASH_TYPECLASS = CommonHashTypeClass::SHA512_224;
}