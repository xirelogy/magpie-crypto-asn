<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\Kdf;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Factories\ClassFactory;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5KdfOid;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;
use MagpieLib\CryptoAsn\Objects\Pkcs5\Algorithm;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;

/**
 * Key derivation functions used in PKCS #5
 * @link https://www.rfc-editor.org/rfc/rfc2898#section-5
 */
abstract class Kdf extends Algorithm
{
    /**
     * Derive key from given password using current function
     * @param BinaryData|string $password
     * @param int|null $derivedKeyLength
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function derive(BinaryData|string $password, ?int $derivedKeyLength, ?AsnDecoderEventHandleable $handle = null) : BinaryData
    {
        $password = BinaryData::acceptBinary($password);

        return $this->onDerive($password, $derivedKeyLength, $handle);
    }


    /**
     * Derive key from given password using current function
     * @param BinaryData $password
     * @param int|null $derivedKeyLength
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected abstract function onDerive(BinaryData $password, ?int $derivedKeyLength, ?AsnDecoderEventHandleable $handle) : BinaryData;


    /**
     * @inheritDoc
     */
    public static final function fromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $oid = $identifier->algorithm->getString();

        $className = ClassFactory::safeResolve($oid, Pkcs5KdfOid::class);
        if ($className === null) {
            $localHandle->warnUnsupported($oid, _l('PKCS #5 KDF'));
            throw new UnsupportedValueException($oid, _l('PKCS #5 KDF'));
        }
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return $className::constructFromAlgorithmIdentifier($identifier, $handle);
    }
}