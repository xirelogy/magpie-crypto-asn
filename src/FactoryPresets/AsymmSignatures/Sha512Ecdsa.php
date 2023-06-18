<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures;

use Magpie\Cryptos\Algorithms\Hashes\CommonHashTypeClass;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\AsymmSignatureOid;
use MagpieLib\CryptoAsn\FactoryPresets\Traits\CommonAsymmSignature;

/**
 * ecdsa-with-SHA512
 */
#[FactoryTypeClass(Sha512Ecdsa::OID, AsymmSignatureOid::class)]
class Sha512Ecdsa extends Ecdsa
{
    use CommonAsymmSignature;

    /**
     * Current OID
     */
    public const OID = '1.2.840.10045.4.3.4';
    /**
     * Current hash algorithm
     */
    public const HASH_TYPECLASS = CommonHashTypeClass::SHA512;
}