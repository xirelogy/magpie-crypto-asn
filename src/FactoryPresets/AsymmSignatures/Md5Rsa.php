<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures;

use Magpie\Cryptos\Algorithms\Hashes\CommonHashTypeClass;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\AsymmSignatureOid;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonAsymmSignature;

/**
 * md5WithRSAEncryption
 */
#[FactoryTypeClass(Md5Rsa::OID, AsymmSignatureOid::class)]
class Md5Rsa extends Rsa
{
    use CommonAsymmSignature;

    /**
     * Current OID
     */
    public const OID = '1.2.840.113549.1.1.4';
    /**
     * Current hash algorithm
     */
    public const HASH_TYPECLASS = CommonHashTypeClass::MD5;
}