<?php

namespace MagpieLib\CryptoAsn\FactoryPresets\AsymmSignatures;

use Magpie\Cryptos\Algorithms\AsymmetricCryptos\PublicKey;
use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\NullException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\General\Factories\ClassFactory;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\OidAssociable;
use MagpieLib\CryptoAsn\Exceptions\CryptoFaultException;
use MagpieLib\CryptoAsn\Factories\AsymmSignatureOid;
use MagpieLib\CryptoAsn\Impls\CommonAsnDecoderEventHandle;
use MagpieLib\CryptoAsn\Syntaxes\AlgorithmIdentifier;

/**
 * Asymmetric signature algorithm
 */
abstract class AsymmSignature implements OidAssociable
{
    /**
     * The asymmetric algorithm
     * @return string
     */
    public abstract function getAsymmAlgoTypeClass() : string;


    /**
     * The hash algorithm
     * @return string
     */
    public abstract function getHashAlgoTypeClass() : string;


    /**
     * If the given public key is supported
     * @param PublicKey $key
     * @return bool
     */
    public abstract function isPublicKeySupported(PublicKey $key) : bool;


    /**
     * Resolve from OID
     * @param string $oid
     * @param AsnDecoderEventHandleable|null $handle
     * @return static|null
     */
    public static function fromOid(string $oid, ?AsnDecoderEventHandleable $handle = null) : ?static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $className = ClassFactory::safeResolve($oid, AsymmSignatureOid::class);
        if ($className === null) return $localHandle->warnUnsupported($oid, _l('Asymmetric signature algorithm OID'));
        if (!is_subclass_of($className, self::class)) return null;

        return new $className();
    }


    /**
     * Resolve from algorithm identifier
     * @param AlgorithmIdentifier $algorithm
     * @param AsnDecoderEventHandleable|null $handle
     * @return static|null
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public static function fromAlgorithmIdentifier(AlgorithmIdentifier $algorithm, ?AsnDecoderEventHandleable $handle = null) : ?static
    {
        $localHandle = CommonAsnDecoderEventHandle::create($handle);

        $oid = $algorithm->algorithm->getString();

        $className = ClassFactory::safeResolve($oid, AsymmSignatureOid::class);
        if ($className === null) return $localHandle->warnUnsupported($oid, _l('Asymmetric signature algorithm OID'));
        if (!is_subclass_of($className, self::class)) return null;

        return $className::constructFromAlgorithmIdentifier($algorithm, $handle);
    }


    /**
     * Construct form algorithm identifier
     * @param AlgorithmIdentifier $algorithm
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function constructFromAlgorithmIdentifier(AlgorithmIdentifier $algorithm, ?AsnDecoderEventHandleable $handle) : static
    {
        _used($algorithm, $handle);

        _throwable(1) ?? throw new NullException();
        _throwable(2) ?? throw new CryptoFaultException();

        return new static();
    }
}