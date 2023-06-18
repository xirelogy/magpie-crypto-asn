<?php

namespace MagpieLib\CryptoAsn\Objects\Pkcs5\Scheme;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\ClassNotOfTypeException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnsupportedValueException;
use Magpie\General\Factories\ClassFactory;
use Magpie\Objects\BinaryData;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Factories\Pkcs5SchemeOid;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;
use MagpieLib\CryptoAsn\Objects\Pkcs5\Algorithm;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;

/**
 * A PKCS#5 encryption scheme
 * @link https://www.rfc-editor.org/rfc/rfc2898#section-6
 */
abstract class Scheme extends Algorithm
{
    /**
     * Decrypts the payload using current scheme and given password
     * @param BinaryData|string $payload
     * @param BinaryData|string $password
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public final function decrypt(BinaryData|string $payload, BinaryData|string $password, ?AsnDecoderEventHandleable $handle = null) : BinaryData
    {
        $payload = BinaryData::acceptBinary($payload);
        $password = BinaryData::acceptBinary($password);

        return $this->onDecrypt($payload, $password, $handle);
    }


    /**
     * Decrypts the payload using current scheme and given password
     * @param BinaryData $payload
     * @param BinaryData $password
     * @param AsnDecoderEventHandleable|null $handle
     * @return BinaryData
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected abstract function onDecrypt(BinaryData $payload, BinaryData $password, ?AsnDecoderEventHandleable $handle) : BinaryData;


    /**
     * @inheritDoc
     */
    public static final function fromAlgorithmIdentifier(AlgorithmIdentifier $identifier, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $oid = $identifier->algorithm->getString();

        $className = ClassFactory::safeResolve($oid, Pkcs5SchemeOid::class);
        if ($className === null) {
            $localHandle->warnUnsupported($oid, _l('PKCS #5 encryption scheme'));
            throw new UnsupportedValueException($oid, _l('PKCS #5 encryption scheme'));
        }
        if (!is_subclass_of($className, self::class)) throw new ClassNotOfTypeException($className, self::class);

        return $className::constructFromAlgorithmIdentifier($identifier, $handle);
    }
}