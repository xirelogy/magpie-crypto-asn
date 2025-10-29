<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\Cryptos\Algorithms\SymmetricCryptos\CipherSetup;
use Magpie\Cryptos\Algorithms\SymmetricCryptos\CommonCipherAlgoTypeClass;
use Magpie\Cryptos\Algorithms\SymmetricCryptos\CommonCipherMode;
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

        $ret = CipherSetup::initialize(CommonCipherAlgoTypeClass::AES, $numBits, CommonCipherMode::CBC)
            ->withKey($key)
            ->withIv($this->iv)
            ;

        // Padding block size is defined in "FIPS PUB 197 — Advanced Encryption Standard (AES)" at §3.4
        // @link https://csrc.nist.gov/pubs/fips/197/final
        //
        $ret->withPadding(Pkcs7Padding::forCipherSetup($ret));
        return $ret;
    }


    /**
     * Number of bits in key
     * @return int
     */
    protected abstract function getNumBits() : int;
}