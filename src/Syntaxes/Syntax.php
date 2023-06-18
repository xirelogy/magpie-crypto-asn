<?php

namespace MagpieLib\CryptoAsn\Syntaxes;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Objects\CommonObject;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;

/**
 * Common ASN.1 sequence syntax
 */
abstract class Syntax extends CommonObject
{
    /**
     * Export current syntax as ASN.1 element
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public abstract function to() : AsnElement;


    /**
     * Construct from given value according to this syntax
     * @param AsnElement $value
     * @param AsnDecoderEventHandleable|null $handle
     * @return static
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public static abstract function from(AsnElement $value, ?AsnDecoderEventHandleable $handle = null) : static;
}