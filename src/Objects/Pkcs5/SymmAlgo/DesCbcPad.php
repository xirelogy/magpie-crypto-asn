<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\Cryptos\Algorithms\SymmetricCryptos\CipherSetup;
use Magpie\Cryptos\Algorithms\SymmetricCryptos\CommonCipherAlgoTypeClass;
use Magpie\Cryptos\Paddings\Pkcs7Padding;
use Magpie\General\Factories\Annotations\FactoryTypeClass;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5SymmAlgoOid;
use MagpieLib\CryptoAsn\Objects\Traits\CommonPkcs5Algorithm;

/**
 * DES CBC (with padding) symmetric encryption scheme
 */
#[FactoryTypeClass(DesCbcPad::OID, Pkcs5SymmAlgoOid::class)]
class DesCbcPad extends CommonSymmAlgo
{
    use CommonPkcs5Algorithm;

    /**
     * Current OID
     */
    public const OID = '1.3.14.3.2.7';


    /**
     * @inheritDoc
     */
    public function getKeyLength() : int
    {
        return 8;
    }


    /**
     * @inheritDoc
     */
    protected function createCipherSetup(BinaryData $key, ?AsnDecoderEventHandleable $handle) : CipherSetup
    {
        return CipherSetup::initialize(CommonCipherAlgoTypeClass::DES)
            ->withKey($key)
            ->withIv($this->iv)
            ->withMode('cbc')
            ->withPadding(new Pkcs7Padding(8))
            ;
    }
}