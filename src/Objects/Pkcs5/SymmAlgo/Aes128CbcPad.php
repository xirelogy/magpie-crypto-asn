<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\General\Factories\Annotations\FactoryTypeClass;
use MagpieLib\CryptoAsn\Factories\Pkcs5SymmAlgoOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonPkcs5Algorithm;

/**
 * AES-128 CBC (with padding) symmetric encryption scheme
 */
#[FactoryTypeClass(Aes128CbcPad::OID, Pkcs5SymmAlgoOid::class)]
class Aes128CbcPad extends AesCbcPad
{
    use CommonPkcs5Algorithm;

    /**
     * Current OID
     */
    public const OID = '2.16.840.1.101.3.4.1.2';


    /**
     * @inheritDoc
     */
    protected function getNumBits() : int
    {
        return 128;
    }
}