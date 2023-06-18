<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\SymmAlgo;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Factories\ClassFactory;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5SymmAlgoOid;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;
use MagpieLib\CryptoAsn\Objects\Pkcs5\Algorithm;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;

/**
 * A PKCS#5 symmetric encryption scheme
 */
abstract class SymmAlgo extends Algorithm
{
    /**
     * Get the desired key length
     * @return int
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public abstract function getKeyLength() : int;


    /**
     * Decrypt ciphertext into corresponding plaintext
     * @param BinaryData|string $ciphertext
     * @param BinaryData|string $key
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function decrypt(BinaryData|string $ciphertext, BinaryData|string $key, ?AsnDecoderEventHandleable $handle = null) : BinaryData
    {
        $ciphertext = BinaryData::acceptBinary($ciphertext);
        $key = BinaryData::acceptBinary($key);

        return $this->onDecrypt($ciphertext, $key, $handle);
    }


    /**
     * Decrypt ciphertext into corresponding plaintext
     * @param BinaryData $ciphertext
     * @param BinaryData $key
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected abstract function onDecrypt(BinaryData $ciphertext, BinaryData $key, ?AsnDecoderEventHandleable $handle) : BinaryData;


    /**
     * @inheritDoc
     */
    public static final function fromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $oid = $identifier->algorithm->getString();

        $className = ClassFactory::safeResolve($oid, Pkcs5SymmAlgoOid::class);
        if ($className === null) {
            $localHandle->warnUnsupported($oid, _l('PKCS #5 symmetric encryption scheme'));
            throw new UnsupportedValueException($oid, _l('PKCS #5 symmetric encryption scheme'));
        }
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return $className::constructFromAlgorithmIdentifier($identifier, $handle);
    }
}