<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures;

use Magpie\Cryptos\Algorithms\Hashes\CommonHashTypeClass;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\AsymmSignatureOid;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonAsymmSignature;

/**
 * sha1WithRSAEncryption
 */
#[FactoryTypeClass(Sha1Rsa::OID, AsymmSignatureOid::class)]
class Sha1Rsa extends Rsa
{
    use CommonAsymmSignature;

    /**
     * Current OID
     */
    public const OID = '1.2.840.113549.1.1.5';
    /**
     * Current hash algorithm
     */
    public const HASH_TYPECLASS = CommonHashTypeClass::SHA1;
}