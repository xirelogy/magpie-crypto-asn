<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\Cryptos\Algorithms\SymmetricCryptos\CipherSetup;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Asn1\AsnOctetString;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;

/**
 * Common PKCS#5 symmetric encryption scheme
 */
abstract class CommonSymmAlgo extends SymmAlgo
{
    /**
     * @var BinaryData Initialization vector
     */
    protected readonly BinaryData $iv;


    /**
     * Constructor
     * @param AlgorithmIdentifier $identifier
     * @param BinaryData $iv
     */
    protected function __construct(AlgorithmIdentifier $identifier, BinaryData $iv)
    {
        parent::__construct($identifier);

        $this->iv = $iv;
    }


    /**
     * @inheritDoc
     */
    protected final function onDecrypt(BinaryData $ciphertext, BinaryData $key, ?AsnDecoderEventHandleable $handle) : BinaryData
    {
        $setup = $this->createCipherSetup($key, $handle);
        return $setup->create()->decrypt($ciphertext);
    }


    /**
     * Create a cipher setup
     * @param BinaryData $key
     * @param AsnDecoderEventHandleable|null $handle
     * @return CipherSetup
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected abstract function createCipherSetup(BinaryData $key, ?AsnDecoderEventHandleable $handle) : CipherSetup;


    /**
     * @inheritDoc
     */
    protected static function constructFromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle) : static
    {
        $parameters = static::requiresAlgorithmIdentifierParameters($identifier);
        $iv = AsnOctetString::cast($parameters)->getString();

        return new static($identifier, $iv);
    }
}