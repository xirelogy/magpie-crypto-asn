<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\Cryptos\Algorithms\SymmetricCryptos\CipherSetup;
use Magpie\Cryptos\Algorithms\SymmetricCryptos\CommonCipherAlgoTypeClass;
use Magpie\Cryptos\Algorithms\SymmetricCryptos\CommonCipherMode;
use Magpie\Cryptos\Paddings\Pkcs7Padding;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5SymmAlgoOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonPkcs5Algorithm;

/**
 * DES-EDE3 CBC (with padding) symmetric encryption scheme
 */
#[FactoryTypeClass(DesEde3CbcPad::OID, Pkcs5SymmAlgoOid::class)]
class DesEde3CbcPad extends CommonSymmAlgo
{
    use CommonPkcs5Algorithm;

    /**
     * Current OID
     */
    public const OID = '1.2.840.113549.3.7';


    /**
     * @inheritDoc
     */
    public function getKeyLength() : int
    {
        return 24;
    }


    /**
     * @inheritDoc
     */
    protected function createCipherSetup(BinaryData $key, ?AsnDecoderEventHandleable $handle) : CipherSetup
    {
        return CipherSetup::initialize(CommonCipherAlgoTypeClass::TRIPLE_DES_EDE3, CommonCipherMode::CBC)
            ->withKey($key)
            ->withIv($this->iv)
            ->withPadding(new Pkcs7Padding(8))
            ;
    }
}