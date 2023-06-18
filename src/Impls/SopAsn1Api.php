<?php

namespace MagpieLib\CryptoAsn\Impls;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\General\Traits\StaticClass;
use MagpieLib\CryptoAsn\Exceptions\CryptoDerDecodeException;
use MagpieLib\CryptoAsn\Exceptions\CryptoFaultException;
use Sop\ASN1\Type\UnspecifiedType as SopAsn1UnspecifiedType;
use Throwable;

/**
 * Binding interface to call functions in sop/asn1
 * @internal
 */
class SopAsn1Api
{
    use StaticClass;

    /**
     * Decode into ASN.1 element from given DER
     * @param string $binData
     * @return SopAsn1UnspecifiedType
     * @throws CryptoException
     */
    public static function decodeDer(string $binData) : SopAsn1UnspecifiedType
    {
        try {
            return SopAsn1UnspecifiedType::fromDER($binData);
        } catch (Throwable $ex) {
            throw new CryptoDerDecodeException(subMessage: $ex->getMessage(), previous: $ex);
        }
    }


    /**
     * Wrap exception locally
     * @param callable():mixed $fn
     * @return mixed
     * @throws CryptoException
     */
    public static function wrapped(callable $fn) : mixed
    {
        try {
            return $fn();
        } catch (Throwable $ex) {
            throw new CryptoFaultException(previous: $ex);
        }
    }
}