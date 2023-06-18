<?php

namespace MagpieLib\CryptoAsn\Strategies;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\NullException;
use Magpie\Exceptions\SafetyCommonException;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnTaggedElement;
use MagpieLib\CryptoAsn\Exceptions\CryptoFaultException;

/**
 * Context during ASN.1 decoding
 */
class AsnDecoderContext
{
    /**
     * Handle a tagged element
     * @param AsnTaggedElement $el
     * @return AsnElement
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    public function handleTagged(AsnTaggedElement $el) : AsnElement
    {
        _throwable(1) ?? throw new NullException();
        _throwable(2) ?? throw new CryptoFaultException();

        return $el;
    }
}