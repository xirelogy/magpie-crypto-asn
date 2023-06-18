<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\Cryptos\Algorithms\SymmetricCryptos\CipherSetup;
use Magpie\Cryptos\Algorithms\SymmetricCryptos\CommonCipherAlgoTypeClass;
use Magpie\Cryptos\Paddings\Pkcs7Padding;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * AES-based CBC (with padding) symmetric encryption scheme
 */
abstract class AesCbcPad extends CommonSymmAlgo
{
    /**
     * @inheritDoc
     */
    public final function getKeyLength() : int
    {
        $numBits = $this->getNumBits();
        return floor($numBits / 8);
    }


    /**
     * @inheritDoc
     */
    protected final function createCipherSetup(BinaryData $key, ?AsnDecoderEventHandleable $handle) : CipherSetup
    {
        $numBits = $this->getNumBits();
        $blockSize = floor($numBits / 8);

        return CipherSetup::initialize(CommonCipherAlgoTypeClass::AES, $numBits)
            ->withKey($key)
            ->withIv($this->iv)
            ->withMode('cbc')
            ->withPadding(new Pkcs7Padding($blockSize))
            ;
    }


    /**
     * Number of bits in key
     * @return int
     */
    protected abstract function getNumBits() : int;
}