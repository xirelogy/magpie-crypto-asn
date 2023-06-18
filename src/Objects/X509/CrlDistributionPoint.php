<?php

namespace MagpieLib\CryptoAsn\Objects\X509;

use Magpie\Cryptos\Exceptions\CryptoException;
use Magpie\Exceptions\SafetyCommonException;
use Magpie\Exceptions\UnexpectedException;
use Magpie\Objects\CommonObject;
use Magpie\Objects\Traits\CommonObjectPackAll;
use MagpieLib\CryptoAsn\Asn1\AsnBitString;
use MagpieLib\CryptoAsn\Asn1\AsnElement;
use MagpieLib\CryptoAsn\Asn1\AsnSequence;
use MagpieLib\CryptoAsn\Concepts\AsnDecoderEventHandleable;
use MagpieLib\CryptoAsn\Concepts\AsnStaticDecodable;
use MagpieLib\CryptoAsn\Constants\X509CrlDistributionPointReason;
use MagpieLib\CryptoAsn\Objects\X509\GeneralNames\GeneralName;
use MagpieLib\CryptoAsn\Syntaxes\X501\Name;

/**
 * CRL distribution point
 */
class CrlDistributionPoint extends CommonObject implements AsnStaticDecodable
{
    use CommonObjectPackAll;

    /**
     * @var array<GeneralName>|Name|null Distribution point (name)
     */
    public readonly array|Name|null $distributionPoint;
    /**
     * @var array<int>|null reasons
     */
    public readonly ?array $reasons;
    /**
     * @var array|null CRL issuer (name)
     */
    public readonly ?array $crlIssuer;


    /**
     * Constructor
     * @param array|Name|null $distributionPoint
     * @param array|null $reasons
     * @param array|null $crlIssuer
     */
    protected function __construct(array|Name|null $distributionPoint, ?array $reasons, ?array $crlIssuer)
    {
        $this->distributionPoint = $distributionPoint;
        $this->reasons = $reasons;
        $this->crlIssuer = $crlIssuer;
    }


    /**
     * @inheritDoc
     */
    public static function decode(AsnElement $element, ?AsnDecoderEventHandleable $handle = null) : static
    {
        $element = AsnSequence::cast($element);
        $cursor = $element->iterate();

        $distributionPoint = null;
        $reasons = null;
        $crlIssuer = null;

        $distributionTaggedElement = $cursor->getTaggedElement(0);
        if ($distributionTaggedElement !== null) {
            $distributionElement = $distributionTaggedElement->implicit(AsnSequence::class);
            $distributionPoint = static::decodePointName($distributionElement, $handle);
        }

        $reasonsTaggedElement = $cursor->getTaggedElement(1);
        if ($reasonsTaggedElement !== null) {
            $reasonsElement = AsnBitString::cast($reasonsTaggedElement->implicit(AsnBitString::class));
            $reasons = iter_flatten(X509CrlDistributionPointReason::fromBigEndianBytes($reasonsElement->getString()), false);
        }

        $crlIssuerTaggedElement = $cursor->getTaggedElement(2);
        if ($crlIssuerTaggedElement !== null) {
            $crlIssuerElement = $crlIssuerTaggedElement->implicit(AsnSequence::class);
            $crlIssuer = iter_flatten(static::decodeGeneralNames($crlIssuerElement, $handle), false);
        }

        return new static($distributionPoint, $reasons, $crlIssuer);
    }


    /**
     * @param AsnElement $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return array<GeneralName>|Name
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodePointName(AsnElement $element, ?AsnDecoderEventHandleable $handle) : array|Name
    {
        $element = AsnSequence::cast($element);
        $cursor = $element->iterate();

        $fullNameTaggedElement = $cursor->getTaggedElement(0);
        if ($fullNameTaggedElement !== null) {
            $fullNameElement = $fullNameTaggedElement->implicit(AsnSequence::class);
            return iter_flatten(static::decodeGeneralNames($fullNameElement, $handle), false);
        }

        $relativeTaggedElement = $cursor->getTaggedElement(1);
        if ($relativeTaggedElement !== null) {
            $relativeElement = $relativeTaggedElement->implicit(AsnSequence::class);
            return Name::from($relativeElement, $handle);
        }

        throw new UnexpectedException();
    }


    /**
     * Decode all general names
     * @param AsnElement $element
     * @param AsnDecoderEventHandleable|null $handle
     * @return iterable<GeneralName>
     * @throws SafetyCommonException
     * @throws CryptoException
     */
    protected static function decodeGeneralNames(AsnElement $element, ?AsnDecoderEventHandleable $handle) : iterable
    {
        $element = AsnSequence::cast($element);
        foreach ($element->getElements() as $subElement) {
            yield GeneralName::decode($subElement, $handle);
        }
    }
}