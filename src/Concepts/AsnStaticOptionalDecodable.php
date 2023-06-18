<?php

namespace MagpieLib\CryptoAsn\Concepts;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use MagpieLib\CryptoAsn\Asn1\AsnElement;

/**
 * May optionally decode (statically) from ASN.1
 */
interface AsnStaticOptionalDecodable
{
    /**
     * Decode optionally from ASN
     * @param AsnElement $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return static|null
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : ?static;
}